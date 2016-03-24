using System;
using System.Collections.Generic;
using System.Configuration;
using System.IO;
using System.Linq;
using System.Security.Cryptography;
using System.Text;
using System.Threading.Tasks;

namespace Crypto_Program
{
    class DESMethod
    {
        public string EncryptText(string verzender, string ontvanger, string encryptieFilePath, string boodschap)
        {
            string folder = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);
            string filesFolder = System.IO.Path.Combine(folder, "CryptoProgram/files");
            string keysFolder = System.IO.Path.Combine(folder, "CryptoProgram/keys");
            string outputFile1 = System.IO.Path.Combine(filesFolder, "File_1.txt");
            string outputFile2 = System.IO.Path.Combine(filesFolder, "File_2.txt");
            string outputFile3 = System.IO.Path.Combine(filesFolder, "File_3.txt");

            // create des, key and IV
            DES des = DES.Create();

            byte[] encData = File.ReadAllBytes(encryptieFilePath);
            EncryptFile(encData, outputFile1, des.Key, des.IV);

            string encryptedTekst = File.ReadAllText(outputFile1);

            string ontvangerPublicKey = System.IO.Path.Combine(keysFolder, "Public_" + ontvanger + ".txt");
            string publicOntvanger = File.ReadAllText(ontvangerPublicKey);
            EncryptDesKey(publicOntvanger, des.Key, des.IV, outputFile2);

            string verzenderPublicKey = System.IO.Path.Combine(keysFolder, "Private_" + verzender + ".txt");
            string privateVerzender = File.ReadAllText(verzenderPublicKey);
            
            MD5 hash = MD5.Create();
            byte[] hashBoodschap = hash.ComputeHash(Encoding.UTF8.GetBytes(boodschap));

            Encrypt(hashBoodschap, privateVerzender, outputFile3);

            return encryptedTekst;
        }

        private void EncryptFile(Byte[] Data, String FileName, byte[] Key, byte[] IV)
        {
            FileStream fStream = File.Open(FileName, FileMode.Create);
            DES DESalg = DES.Create();
            CryptoStream cStream = new CryptoStream(fStream, DESalg.CreateEncryptor(Key, IV), CryptoStreamMode.Write);
            cStream.Write(Data, 0, Data.Length);
            cStream.Close();
            fStream.Close();
        }

        private void EncryptDesKey(string keyName, byte[] Key, byte[] IV, string outputFile)
        {
            byte[] encKey;
            byte[] encIV;

            using (RSACryptoServiceProvider rsa = new RSACryptoServiceProvider())
            {
                rsa.FromXmlString(keyName);
                encKey = rsa.Encrypt(Key, false);
                encIV = rsa.Encrypt(IV, false);
            }

            string keyIV = Convert.ToBase64String(encKey) + ":" + Convert.ToBase64String(encIV);
            File.WriteAllText(outputFile, keyIV);
        }

        private void Encrypt(byte[] hash, string privateKey, string outputFile)
        {

            byte[] byteArray = hash;
            byte[] EncryptedFile;
            string privateKeyString = privateKey;

            RSACryptoServiceProvider rsa = new RSACryptoServiceProvider();
            rsa.FromXmlString(privateKeyString);
            EncryptedFile = rsa.Encrypt(byteArray, false);
            string stuff = Convert.ToBase64String(EncryptedFile);
            File.WriteAllText(outputFile, stuff);

        }
    }
}
