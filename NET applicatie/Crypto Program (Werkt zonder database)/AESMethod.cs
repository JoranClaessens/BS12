using System;
using System.Collections.Generic;
using System.Configuration;
using System.IO;
using System.IO.Compression;
using System.Linq;
using System.Security.Cryptography;
using System.Text;
using System.Threading.Tasks;
using System.Windows;

namespace Crypto_Program
{
    class AESMethod
    {
        public string EncryptText(string verzender, string ontvanger, string encryptieFilePath, string boodschap)
        {
            string folder = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);
            string filesFolder = System.IO.Path.Combine(folder, "CryptoProgram/files");
            string editFolder = System.IO.Path.Combine(folder, "CryptoProgram/files/edit");

            if (!Directory.Exists(editFolder))
            {
                Directory.CreateDirectory(editFolder);
            }

            string keysFolder = System.IO.Path.Combine(folder, "CryptoProgram/keys");
            string outputFile1 = System.IO.Path.Combine(editFolder, "File_1.txt");
            string outputFile2 = System.IO.Path.Combine(editFolder, "File_2.txt");
            string outputFile3 = System.IO.Path.Combine(editFolder, "File_3.txt");
            string encryptedTekst = null;

            try
            {
                // Create a new instance of the AesManaged 
                // class.  This generates a new key and initialization  
                // vector (IV). 
                using (AesManaged aes = new AesManaged())
                {
                    aes.GenerateKey();
                    aes.GenerateIV();

                    // Encrypt the string to an array of bytes. 
                    EncryptFile(encryptieFilePath, outputFile1, aes.Key, aes.IV);

                    encryptedTekst = File.ReadAllText(outputFile1);

                    string ontvangerPublicKey = System.IO.Path.Combine(keysFolder, "Public_" + ontvanger + ".txt");
                    string publicOntvanger = File.ReadAllText(ontvangerPublicKey);
                    EncryptDesKey(publicOntvanger, outputFile2, aes.Key, aes.IV);

                    string verzenderPrivateKey = System.IO.Path.Combine(keysFolder, "Private_" + verzender + ".txt");
                    string privateVerzender = File.ReadAllText(verzenderPrivateKey);

                    SHA1Managed SHhash = new SHA1Managed();
                    byte[] hashBoodschap = SHhash.ComputeHash(Encoding.UTF8.GetBytes(boodschap));

                    Encrypt(hashBoodschap, privateVerzender, outputFile3);

                    string userFile = System.IO.Path.Combine(filesFolder, ontvanger + "_" + DateTime.Now.GetHashCode() + ".zip");

                    ZipFile.CreateFromDirectory(editFolder, userFile);
                }
            }
            catch (Exception e)
            {
                Console.WriteLine("Error: {0}", e.Message);
            }

            return encryptedTekst;
        }

        private void EncryptFile(string sInputFilename, string sOutputFilename, byte[] Key, byte[] IV)
        {
            // Check arguments. 
            string plainText = File.ReadAllText(sInputFilename);

            if (plainText == null || plainText.Length <= 0)
                throw new ArgumentNullException("plainText");
            if (Key == null || Key.Length <= 0)
                throw new ArgumentNullException("Key");
            if (IV == null || IV.Length <= 0)
                throw new ArgumentNullException("IV");
            byte[] encrypted;
            // Create an AesManaged object 
            // with the specified key and IV. 
            using (AesManaged aes = new AesManaged())
            {
                aes.Key = Key;
                aes.IV = IV;

                // Create a decryptor to perform the stream transform.
                ICryptoTransform encryptor = aes.CreateEncryptor(aes.Key, aes.IV);

                // Create the streams used for encryption. 
                using (MemoryStream msEncrypt = new MemoryStream())
                {
                    using (CryptoStream csEncrypt = new CryptoStream(msEncrypt, encryptor, CryptoStreamMode.Write))
                    {
                        using (StreamWriter swEncrypt = new StreamWriter(csEncrypt))
                        {
                            //Write all data to the stream.
                            swEncrypt.Write(plainText);
                        }
                        encrypted = msEncrypt.ToArray();
                    }
                }
            }

            string encryptedText = Convert.ToBase64String(encrypted);
            File.WriteAllText(sOutputFilename, encryptedText);
        }

        private void EncryptDesKey(string keyName, string outputFile, byte[] key, byte[] IV)
        {
            byte[] encKey;
            byte[] encIV;

            using (RSACryptoServiceProvider rsa = new RSACryptoServiceProvider())
            {
                rsa.FromXmlString(keyName);
                encKey = rsa.Encrypt(key, true);
                encIV = rsa.Encrypt(IV, true);
            }

            string keyIV = Convert.ToBase64String(encKey) + ":" + Convert.ToBase64String(encIV);
            File.WriteAllText(outputFile, keyIV);
        }

        private void Encrypt(byte[] hash, string privateKey, string outputFile)
        {
            byte[] byteArray = hash;
            byte[] EncryptedFile;

            RSACryptoServiceProvider rsa = new RSACryptoServiceProvider();
            rsa.FromXmlString(privateKey);
            EncryptedFile = rsa.SignHash(byteArray, CryptoConfig.MapNameToOID("SHA1"));

            string signedHash = Convert.ToBase64String(EncryptedFile);
            File.WriteAllText(outputFile, signedHash);
        }

        public string DecryptDesKey(string file, string gebruiker)
        {
            string folder = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);
            string keysFolder = System.IO.Path.Combine(folder, "CryptoProgram/keys");

            byte[] encKey;
            byte[] encIV;
            string encKeyIV = "";

            byte[] DecKey;
            byte[] DecIV;

            using (StreamReader sr = File.OpenText(file))
            {
                encKeyIV = sr.ReadToEnd();
            }

            char[] seperator = { ':' };
            string[] split = encKeyIV.Split(seperator);
            encKey = Convert.FromBase64String(split[0]);
            encIV = Convert.FromBase64String(split[1]);

            using (RSACryptoServiceProvider rsa = new RSACryptoServiceProvider())
            {
                string inputFile = System.IO.Path.Combine(keysFolder, "Private_" + gebruiker + ".txt");
                string Private_Bxml = File.ReadAllText(inputFile);
                rsa.FromXmlString(Private_Bxml);
                DecKey = rsa.Decrypt(encKey, true);
                DecIV = rsa.Decrypt(encIV, true);
            }

            string AESkey = Convert.ToBase64String(DecKey) + ":" + Convert.ToBase64String(DecIV);

            return AESkey;
        }

        public void DecryptFile(string sInputFilename,
                    string sOutputFilename,
                    byte[] Key, byte[] IV)
        {
            string encryptedText = File.ReadAllText(sInputFilename);
            byte[] cipherText = Convert.FromBase64String(encryptedText);

            // Check arguments. 
            if (cipherText == null || cipherText.Length <= 0)
                throw new ArgumentNullException("cipherText");
            if (Key == null || Key.Length <= 0)
                throw new ArgumentNullException("Key");
            if (IV == null || IV.Length <= 0)
                throw new ArgumentNullException("IV");

            // Declare the string used to hold 
            // the decrypted text. 
            string plaintext = null;

            // Create an AesManaged object 
            // with the specified key and IV. 
            using (AesManaged aes = new AesManaged())
            {
                aes.Key = Key;
                aes.IV = IV;

                // Create a decrytor to perform the stream transform.
                ICryptoTransform decryptor = aes.CreateDecryptor(aes.Key, aes.IV);

                // Create the streams used for decryption. 
                using (MemoryStream msDecrypt = new MemoryStream(cipherText))
                {
                    using (CryptoStream csDecrypt = new CryptoStream(msDecrypt, decryptor, CryptoStreamMode.Read))
                    {
                        using (StreamReader srDecrypt = new StreamReader(csDecrypt))
                        {
                            // Read the decrypted bytes from the decrypting stream 
                            // and place them in a string.
                            plaintext = srDecrypt.ReadToEnd();
                        }
                    }
                }
            }

            File.WriteAllText(sOutputFilename, plaintext);
        }

        public bool VerifyHash(string file, string publicKey)
        {
            string fileText = File.ReadAllText(file);
            byte[] cipherText = Encoding.UTF8.GetBytes(fileText);

            bool validHash = true;

            string folder = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);
            string inputFile1 = System.IO.Path.Combine(folder, "CryptoProgram/files/DecryptedFile.txt");

            SHA1Managed SHhash = new SHA1Managed();
            string text = File.ReadAllText(inputFile1);
            byte[] hash_decrypted = SHhash.ComputeHash(Encoding.UTF8.GetBytes(text));

            RSACryptoServiceProvider rsa = new RSACryptoServiceProvider();
            string publicKeystring = File.ReadAllText(publicKey);
            rsa.FromXmlString(publicKeystring);

            validHash = rsa.VerifyHash(hash_decrypted, CryptoConfig.MapNameToOID("SHA1"), Convert.FromBase64String(fileText));

            return validHash;
        }
    }
}
