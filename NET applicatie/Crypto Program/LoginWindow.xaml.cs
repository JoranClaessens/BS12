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
        private string file;
        
        public LoginWindow()
        {
            InitializeComponent();

            aanmeldButton.Click += aanmeldButton_Click;
            registreerButton.Click += registreerButton_Click;

            folder = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);
            specificFolder = System.IO.Path.Combine(folder, "CryptoProgram");

            if (!Directory.Exists(specificFolder))
            {
                Directory.CreateDirectory(specificFolder);
            }

            file = System.IO.Path.Combine(specificFolder, "gebruikers.txt");

            if (!File.Exists(file))
            {
                using (StreamWriter writer = File.CreateText(file))
                {
                    string paswoordAlice = PaswoordEncryptie.ComputeHash("Paswoord1", "SHA256", null);
                    string paswoordBob = PaswoordEncryptie.ComputeHash("Paswoord2", "SHA256", null);

                    writer.WriteLine("Alice," + paswoordAlice);
                    writer.WriteLine("Bob," + paswoordBob);
                }
            }
        }

        void aanmeldButton_Click(object sender, RoutedEventArgs e)
        {
            string gebruiker = gebruikerBox.Text.ToString();
            string paswoord = paswoordBox.Password;
            bool succesGebruiker = false;
            bool succesPaswoord = false;

            using (StreamReader reader = new StreamReader(file))
            {
                string line =  reader.ReadLine();

                while(line != null && !succesPaswoord)
                {
                    string[] lines = line.Split(',');
                    if (lines[0] == gebruiker) 
                    {
                        succesGebruiker = true;
                        succesPaswoord = PaswoordEncryptie.VerifyHash(paswoord, "SHA256", lines[1]);
                    }
                    line = reader.ReadLine();
                }

                if (succesPaswoord)
                {
                    MessageBox.Show("Succesvol ingelogd");
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
        }

        void registreerButton_Click(object sender, RoutedEventArgs e)
        {
            RegistreerWindow registreerWindow = new RegistreerWindow();
            registreerWindow.ShowDialog();
        }
    }
}
