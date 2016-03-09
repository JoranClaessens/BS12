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

            try
            {
                if (Validatie.ValideerGebruiker(gebruiker)) 
                {
                    if (Validatie.ValideerPaswoord(paswoord))
                    {
                        string paswoordHash = PaswoordEncryptie.ComputeHash(paswoord, "SHA1", null);

                        string folder = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);
                        string specificFolder = System.IO.Path.Combine(folder, "CryptoProgram");
                        string file = System.IO.Path.Combine(specificFolder, "gebruikers.txt");

                        using (StreamWriter writer = File.AppendText(file))
                        {
                            writer.Write(gebruiker + "," + paswoordHash);
                            writer.Close();
                        }

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
