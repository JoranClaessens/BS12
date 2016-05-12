using Crypto_Program.Models;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Reflection;
using System.Security.Cryptography;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;

namespace Crypto_Program
{
    /// <summary>
    /// Interaction logic for Login.xaml
    /// </summary>
    public partial class LoginWindow : Window
    {
        private string folder;
        private string specificFolder;
        private BS12Entities BS12;
        
        public LoginWindow()
        {
            InitializeComponent();

            BS12 = new BS12Entities();

            //BS12Context BS12c = new BS12Context();

            aanmeldButton.Click += aanmeldButton_Click;
            registreerButton.Click += registreerButton_Click;

            gebruikerBox.KeyDown += gebruikerBox_KeyDown;
            gebruikerBox.Focus();
            paswoordBox.KeyDown += paswoordBox_KeyDown;

            folder = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);
            specificFolder = System.IO.Path.Combine(folder, "CryptoProgram");

            // Create Cryptoprogram folder if not exist
            if (!Directory.Exists(specificFolder))
            {
                Directory.CreateDirectory(specificFolder);
            }

            string keyFolder = System.IO.Path.Combine(specificFolder, "keys");

            // Create keys folder if not exist
            if (!Directory.Exists(keyFolder))
            {
                Directory.CreateDirectory(keyFolder);
            }

            string fileFolder = System.IO.Path.Combine(specificFolder, "files");

            // Create files folder if not exist
            if (!Directory.Exists(fileFolder))
            {
                Directory.CreateDirectory(fileFolder);
            }

            string exampleFolder = System.IO.Path.Combine(specificFolder, "examples");

            // Create examples folder if not exist
            if (!Directory.Exists(exampleFolder))
            {
                Directory.CreateDirectory(exampleFolder);

                // Aanmaken testfile_1.txt
                string exampleFile1 = System.IO.Path.Combine(exampleFolder, "testfile_1.txt");
                string textFile1 = Properties.Resources.testfile_1;
                File.WriteAllText(exampleFile1, textFile1);

                // Aanmaken testfile_2.txt
                string exampleFile2 = System.IO.Path.Combine(exampleFolder, "testfile_2.txt");
                string textFile2 = Properties.Resources.testfile_2;
                File.WriteAllText(exampleFile2, textFile2);

                // Aanmaken testfile_3.txt
                string exampleFile3 = System.IO.Path.Combine(exampleFolder, "testfile_3.txt");
                string textFile3 = Properties.Resources.testfile_3;
                File.WriteAllText(exampleFile3, textFile3);
            }
        }

        void gebruikerBox_KeyDown(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Enter)
            {
                paswoordBox.Focus();
            }
        }

        void paswoordBox_KeyDown(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Enter)
            {
                Aanmelden();
            }
        }

        void aanmeldButton_Click(object sender, RoutedEventArgs e)
        {
            Aanmelden();
        }

        private void Aanmelden()
        {
            string gebruiker = gebruikerBox.Text.ToString();
            string paswoord = paswoordBox.Password;
            bool succesGebruiker = false;
            bool succesPaswoord = false;

            BS12 = new BS12Entities();

            // Checken of de gebruiker bestaat in de database
            var aantalRijen = (from userDB in BS12.Gebruikers
                               where userDB.Gebruikersnaam == gebruiker
                               select userDB).Count();

            // Indien de gebruiker niet bestaat, zal deze if niet uitgevoerd worden
            if (aantalRijen > 0)
            {
                // Gegevens opvragen van deze gebruiker
                var gebruikerDB = (from userDB in BS12.Gebruikers
                            where userDB.Gebruikersnaam == gebruiker
                            select userDB).First();

                succesGebruiker = true;

                // Paswoord controleren
                succesPaswoord = PaswoordEncryptie.VerifyHash(paswoord, "SHA256", gebruikerDB.Paswoord);
            }

            if (succesPaswoord)
            {
                this.Hide();
                HomeWindow homeWindow = new HomeWindow(gebruiker);
                homeWindow.ShowDialog();
            }
            else if (succesGebruiker)
            {
                MessageBox.Show("Verkeerd paswoord!");
            }
            else
            {
                MessageBox.Show("Deze gebruiker bestaat niet.");
            }
        }

        void registreerButton_Click(object sender, RoutedEventArgs e)
        {
            RegistreerWindow registreerWindow = new RegistreerWindow();
            registreerWindow.ShowDialog();
        }
    }
}
