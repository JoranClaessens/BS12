using System;
using System.Collections.Generic;
using System.Linq;
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
using System.IO;

namespace Crypto_Program
{
    /// <summary>
    /// Interaction logic for Registreer.xaml
    /// </summary>
    public partial class RegistreerWindow : Window
    {
        public RegistreerWindow()
        {
            InitializeComponent();

            gebruikerBox.Focus();

            gebruikerBox.KeyDown += gebruikerBox_KeyDown;
            paswoordBox.KeyDown += paswoordBox_KeyDown;
            voornaamBox.KeyDown += voornaamBox_KeyDown;
            achternaamBox.KeyDown += achternaamBox_KeyDown;

            accountButton.Click += accountButton_Click;
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
                voornaamBox.Focus();
            }
        }

        void voornaamBox_KeyDown(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Enter)
            {
                achternaamBox.Focus(); ;
            }
        }

        void achternaamBox_KeyDown(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Enter)
            {
                Registreren();
            }
        }

        void accountButton_Click(object sender, RoutedEventArgs e)
        {
            Registreren();
        }

        private void Registreren()
        {
            string gebruiker = gebruikerBox.Text.ToString();
            string paswoord = paswoordBox.Password;

            try
            {
                // Checken of de gebruikersnaam voldoet aan de normen
                if (Validatie.ValideerGebruiker(gebruiker))
                {
                    // Checken of het paswoord voldoet aan de normen
                    if (Validatie.ValideerPaswoord(paswoord))
                    {
                        // Hash creëren van het paswoord
                        string paswoordHash = PaswoordEncryptie.ComputeHash(paswoord, "SHA256", null);

                        string folder = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);
                        string specificFolder = System.IO.Path.Combine(folder, "CryptoProgram");
                        string userFolder = System.IO.Path.Combine(specificFolder, "users.txt");

                        using (StreamWriter writer = File.AppendText(userFolder))
                        {
                            writer.WriteLine(string.Format("{0}#{1}", gebruiker, paswoordHash));
                        }

                        // Een public en private key aanmaken voor deze gebruiker
                        Keys.GenerateRSAKeyPair(gebruiker);

                        this.Close();
                    }
                }
            }
            catch (ArgumentNullException ex)
            {
                MessageBox.Show(ex.Message + "Gebruiker is leeg");
            }
        }
    }
}
