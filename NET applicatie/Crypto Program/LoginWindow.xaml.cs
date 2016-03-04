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
using System.Windows.Shapes;

namespace Crypto_Program
{
    /// <summary>
    /// Interaction logic for Login.xaml
    /// </summary>
    public partial class LoginWindow : Window
    {
        
        public LoginWindow()
        {
            InitializeComponent();

            aanmeldButton.Click += aanmeldButton_Click;
            registreerButton.Click += registreerButton_Click;

            if (!File.Exists("gebruikers.txt"))
            {
                File.Create("gebruikers.txt");
            }
        }

        void aanmeldButton_Click(object sender, RoutedEventArgs e)
        {
            string gebruiker = gebruikerBox.Text.ToString();
            string paswoord = paswoordBox.Password;
            bool succes = false;

            using (StreamReader reader = new StreamReader("gebruikers.txt"))
            {
                string line =  reader.ReadLine();

                while(line != null && !succes)
                {
                    string[] lines = line.Split(',');
                    if (lines[0] == gebruiker) 
                    {
                        succes = PaswoordEncryptie.VerifyHash(paswoord, "SHA1", lines[1]);
                    }
                    line = reader.ReadLine();
                }

                if (succes)
                {
                    MessageBox.Show("Succesvol ingelogd");
                    this.Hide();
                    HomeWindow homeWindow = new HomeWindow();
                    homeWindow.ShowDialog();
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
