using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Security.Cryptography;
using System.Text;
using System.Threading.Tasks;

namespace Crypto_Program
{
    class Encryptie
    {
        private static void GenerateRSAKeyPair(string gebruikersnaam)
        {
            string folder = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);
            string specificFolder = System.IO.Path.Combine(folder, "CryptoProgram/keys");

            RSACryptoServiceProvider rsa = new RSACryptoServiceProvider(2048);
            
            string publicA = rsa.ToXmlString(false);
            string filePublic = System.IO.Path.Combine(specificFolder, "Public_" + gebruikersnaam + ".txt");
            File.WriteAllText(filePublic, publicA);
            
            string privateA = rsa.ToXmlString(true);
            string filePrivate = System.IO.Path.Combine(specificFolder, "Private_" + gebruikersnaam + ".txt");
            File.WriteAllText(filePrivate, privateA);
        }
    }
}
