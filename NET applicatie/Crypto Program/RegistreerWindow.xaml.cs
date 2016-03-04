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
using System.Text;
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

            AccountButton.Click += AccountButton_Click;
        }

        void AccountButton_Click(object sender, RoutedEventArgs e)
        {
            string gebruiker = gebruikerBox.Text.ToString();
            string paswoord = paswoordBox.Password;

            string paswoordHash = PaswoordEncryptie.ComputeHash(paswoord, "SHA1", null);

            using (StreamWriter writer = new StreamWriter("gebruikers.txt"))
            {
                writer.WriteLine(gebruiker + "," + paswoordHash);
                writer.Close();
            }

            this.Close();
        }
    }
}
