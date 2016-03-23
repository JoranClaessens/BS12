using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
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

            aanmeldButton.Click += aanmeldButton_Click;
            registreerButton.Click += registreerButton_Click;

            folder = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);
            specificFolder = System.IO.Path.Combine(folder, "CryptoProgram");

            if (!Directory.Exists(specificFolder))
            {
                Directory.CreateDirectory(specificFolder);
            }

            string keyFolder = System.IO.Path.Combine(specificFolder, "keys");

            if (!Directory.Exists(keyFolder))
            {
                Directory.CreateDirectory(keyFolder);
            }

            string fileFolder = System.IO.Path.Combine(specificFolder, "files");

            if (!Directory.Exists(fileFolder))
            {
                Directory.CreateDirectory(fileFolder);
            }
        }

        void aanmeldButton_Click(object sender, RoutedEventArgs e)
        {
            string gebruiker = gebruikerBox.Text.ToString();
            string paswoord = paswoordBox.Password;
            bool succesGebruiker = false;
            bool succesPaswoord = false;

            BS12 = new BS12Entities();

            var aantalRijen = (from userDB in BS12.Gebruiker
                               where userDB.Gebruikersnaam == gebruiker
                               select userDB).Count();

            if (aantalRijen > 0)
            {
                var user = (from userDB in BS12.Gebruiker
                            where userDB.Gebruikersnaam == gebruiker
                            select userDB).First();

                succesGebruiker = true;
                succesPaswoord = PaswoordEncryptie.VerifyHash(paswoord, "SHA256", user.Paswoord);
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
