using Microsoft.Win32;
using System;
using System.Collections.Generic;
using System.IO;
using System.IO.Compression;
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

namespace Crypto_Program
{
    /// <summary>
    /// Interaction logic for MainWindow.xaml
    /// </summary>
    public partial class HomeWindow : Window
    {
        private string encryptieFilePath;
        private string gebruiker;

        public HomeWindow(string gebruiker)
        {
            InitializeComponent();
            this.gebruiker = gebruiker;

            encryptExpander.MouseEnter += encryptExpander_MouseEnter;
            encryptExpander.MouseLeave += encryptExpander_MouseLeave;

            decryptExpander.MouseEnter += decryptExpander_MouseEnter;
            decryptExpander.MouseLeave += decryptExpander_MouseLeave;

            gebruikerLabel.Content = "Ingelogd als " + gebruiker;
            afmeldButton.Click += afmeldButton_Click;

            menuEncryptTextButton.Click += menuEncryptTextButton_Click;
            menuDecryptTextButton.Click += menuDecryptTextButton_Click;

            zoekButton.Click += zoekButton_Click;
            zoek2Button.Click += zoek2Button_Click;

            encryptTextButton.Click += encryptTextButton_Click;
            decryptTextButton.Click += decryptTextButton_Click;
        }

        void menuEncryptTextButton_Click(object sender, System.Windows.RoutedEventArgs e)
        {
            encryptTextGrid.Visibility = Visibility.Visible;
            decryptTextGrid.Visibility = Visibility.Hidden;

            string gebruiker = Convert.ToString(gebruikerLabel.Content);

            string folder = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);
            string specificFolder = System.IO.Path.Combine(folder, "CryptoProgram");
            string userFolder = System.IO.Path.Combine(specificFolder, "users.txt");

            List<string> users = new List<string>();
            using (StreamReader reader = new StreamReader(userFolder))
            {
                string line = reader.ReadLine();
                while (line != null)
                {
                    string[] user = line.Split('#');
                    users.Add(user[0]);
                    line = reader.ReadLine();
                }
            }

            // Deze gebruikers toevoegen in de combobox
            encryptGebruikerBox.DataContext = users;
        }

        void menuDecryptTextButton_Click(object sender, System.Windows.RoutedEventArgs e)
        {
            encryptTextGrid.Visibility = Visibility.Hidden;
            decryptTextGrid.Visibility = Visibility.Visible;

            string folder = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);
            string specificFolder = System.IO.Path.Combine(folder, "CryptoProgram");
            string userFolder = System.IO.Path.Combine(specificFolder, "users.txt");

            List<string> users = new List<string>();
            using (StreamReader reader = new StreamReader(userFolder))
            {
                string line = reader.ReadLine();
                while (line != null)
                {
                    string[] user = line.Split('#');
                    users.Add(user[0]);
                    line = reader.ReadLine();
                }
            }

            // Deze gebruikers toevoegen in de combobox
            decryptGebruikerBox.DataContext = users;
        }

        void zoekButton_Click(object sender, RoutedEventArgs e)
        {
            OpenFileDialog openFileDialog1 = new OpenFileDialog();

            string appdata = "%appdata%";
            string path = Environment.ExpandEnvironmentVariables(appdata);
            openFileDialog1.InitialDirectory = path + "\\CryptoProgram\\examples";
            openFileDialog1.Filter = "txt files (*.txt)|*.txt|All files (*.*)|*.*";
            openFileDialog1.FilterIndex = 2;
            openFileDialog1.RestoreDirectory = true;

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

        void zoek2Button_Click(object sender, RoutedEventArgs e)
        {
            OpenFileDialog openFileDialog1 = new OpenFileDialog();

            string appdata = "%appdata%";
            string path = Environment.ExpandEnvironmentVariables(appdata);
            openFileDialog1.InitialDirectory = path + "\\CryptoProgram\\files";
            openFileDialog1.Filter = "Zip Files|*.zip";
            openFileDialog1.FilterIndex = 2;
            openFileDialog1.RestoreDirectory = true;

            if (openFileDialog1.ShowDialog() == true)
            {
                try
                {
                    inputDecryptTextBox.Text = openFileDialog1.FileName;
                }
                catch (Exception ex)
                {
                    MessageBox.Show("Error: Could not read file from disk. Original error: " + ex.Message);
                }
            }
        }

        void encryptTextButton_Click(object sender, RoutedEventArgs e)
        {
            AESMethod aes = new AESMethod();
            string encryptedText = aes.EncryptText(gebruiker, encryptGebruikerBox.Text, encryptieFilePath, inputEncryptTextBox.Text);
            outputEncryptTextBox.Text = encryptedText;
        }

        void decryptTextButton_Click(object sender, System.Windows.RoutedEventArgs e)
        {
            string folder = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);
            string filesFolder = System.IO.Path.Combine(folder, "CryptoProgram/files");
            string editFolder = System.IO.Path.Combine(folder, "CryptoProgram/files/edit");
            string keysFolder = System.IO.Path.Combine(folder, "CryptoProgram/keys");
            string inputFile1 = System.IO.Path.Combine(editFolder, "File_1.txt");
            string inputFile2 = System.IO.Path.Combine(editFolder, "File_2.txt");
            string inputFile3 = System.IO.Path.Combine(editFolder, "File_3.txt");

            if (Directory.Exists(editFolder))
            {
                File.Delete(inputFile1);
                File.Delete(inputFile2);
                File.Delete(inputFile3);
                Directory.Delete(editFolder);
                Directory.CreateDirectory(editFolder);
            }
            else
            {
                Directory.CreateDirectory(editFolder);
            }

            ZipFile.ExtractToDirectory(inputDecryptTextBox.Text, editFolder);

            string inputKeyFile = System.IO.Path.Combine(keysFolder, "Public_" + decryptGebruikerBox.Text + ".txt");

            AESMethod aes = new AESMethod();
            string key = aes.DecryptDesKey(inputFile2, gebruiker);

            char[] seperator = { ':' };
            string[] split = key.Split(seperator);
            byte[] decKey = Convert.FromBase64String(split[0]);
            byte[] decIV = Convert.FromBase64String(split[1]);

            string outputFile = System.IO.Path.Combine(filesFolder, "DecryptedFile.txt");
            aes.DecryptFile(inputFile1, outputFile, decKey, decIV);

            string tekst = File.ReadAllText(outputFile);
            outputdecryptTextBox.Text = tekst;

            if (aes.VerifyHash(inputFile3, inputKeyFile))
            {
                MessageBox.Show("Hashes komen overeen!");
            }
            else
            {
                MessageBox.Show("Hashes komen niet overeen!");
            }
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
