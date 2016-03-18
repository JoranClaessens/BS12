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

            accountButton.Click += accountButton_Click;
        }

        void accountButton_Click(object sender, RoutedEventArgs e)
        {
            string gebruiker = gebruikerBox.Text.ToString();
            string paswoord = paswoordBox.Password;

            BS12Entities BS12 = new BS12Entities();

            try
            {
                if (Validatie.ValideerGebruiker(gebruiker)) 
                {
                    if (Validatie.ValideerPaswoord(paswoord))
                    {
                        string paswoordHash = PaswoordEncryptie.ComputeHash(paswoord, "SHA256", null);

                        var id = (from gebruikers in BS12.Gebruiker
                                    select gebruikers).Count(); ;

                        Gebruiker gebruikerDB = new Gebruiker();
                        gebruikerDB.Id = id + 1;
                        gebruikerDB.Gebruikersnaam = gebruiker;
                        Encryptie.GenerateRSAKeyPair(gebruiker);
                        gebruikerDB.Paswoord = paswoordHash;
                        BS12.Gebruiker.Add(gebruikerDB);
                        BS12.SaveChanges();

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
