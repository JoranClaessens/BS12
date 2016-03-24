using Microsoft.Win32;
using System;
using System.Collections.Generic;
using System.IO;
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
using System.Windows.Navigation;
using System.Windows.Shapes;
using System.Windows;

namespace Crypto_Program
{
    /// <summary>
    /// Interaction logic for MainWindow.xaml
    /// </summary>
    public partial class HomeWindow : Window
    {
        private string encryptieFilePath;

        public HomeWindow(string gebruiker)
        {
            InitializeComponent();

            encryptExpander.MouseEnter += encryptExpander_MouseEnter;
            encryptExpander.MouseLeave += encryptExpander_MouseLeave;

            decryptExpander.MouseEnter += decryptExpander_MouseEnter;
            decryptExpander.MouseLeave += decryptExpander_MouseLeave;

            gebruikerLabel.Content = "Ingelogd als " + gebruiker;
            afmeldButton.Click += afmeldButton_Click;

            zoekButton.Click += zoekButton_Click;
            encryptTextButton.Click += encryptTextButton_Click;
        }

        void zoekButton_Click(object sender, RoutedEventArgs e)
        {
            OpenFileDialog openFileDialog1 = new OpenFileDialog();

            openFileDialog1.InitialDirectory = "c:\\" ;
            openFileDialog1.Filter = "txt files (*.txt)|*.txt|All files (*.*)|*.*" ;
            openFileDialog1.FilterIndex = 2 ;
            openFileDialog1.RestoreDirectory = true ;

            if(openFileDialog1.ShowDialog() == true)
            {
                try
                {
                    var file = openFileDialog1.FileName;
                    StreamReader sr = new StreamReader(file);
                    string boodschap = sr.ReadToEnd();
                    inputEncryptTextBox.Text = boodschap;
                    encryptieFilePath = openFileDialog1.FileName;
                }
                catch (Exception ex)
                {
                    MessageBox.Show("Error: Could not read file from disk. Original error: " + ex.Message);
                }
            }
        }

        void encryptTextButton_Click(object sender, RoutedEventArgs e)
        {
            DESMethod des = new DESMethod();
            string encryptedText = des.EncryptText("Alice", "Bob", encryptieFilePath, inputEncryptTextBox.Text);
            outputEncryptTextBox.Text = encryptedText;
        }

        void encryptExpander_MouseEnter(object sender, MouseEventArgs e)
        {
            encryptExpander.IsExpanded = true;
        }

        void encryptExpander_MouseLeave(object sender, MouseEventArgs e)
        {
            encryptExpander.IsExpanded = false;
        }

        void decryptExpander_MouseEnter(object sender, MouseEventArgs e)
        {
            decryptExpander.IsExpanded = true;
        }

        void decryptExpander_MouseLeave(object sender, MouseEventArgs e)
        {
            decryptExpander.IsExpanded = false;
        }

        void afmeldButton_Click(object sender, RoutedEventArgs e)
        {
           MessageBoxResult result = MessageBox.Show("Bent u zeker dat u wilt afmelden?", "Afmelden", MessageBoxButton.YesNo, MessageBoxImage.Question);
           if (result == MessageBoxResult.Yes)
           {
               this.Close();
               LoginWindow loginWindow = new LoginWindow();
               loginWindow.ShowDialog();
           }
        }

    }
}
