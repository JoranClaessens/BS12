﻿using System;
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
        
        public LoginWindow()
        {
            InitializeComponent();

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

            string userFolder = System.IO.Path.Combine(specificFolder, "users.txt");

            // Create user folder if not exist
            if (!File.Exists(userFolder))
            {
                File.Create(userFolder);
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

            string folder = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);
            string specificFolder = System.IO.Path.Combine(folder, "CryptoProgram");
            string userFolder = System.IO.Path.Combine(specificFolder, "users.txt");

            var aantalRijen = 0;
            string paswoordDB = "";
            using (StreamReader reader = new StreamReader(userFolder))
            {
                string line = reader.ReadLine();
                while (line != null)
                {
                    string[] user = line.Split('#');
                    if (user[0] == gebruiker)
                    {
                        aantalRijen = 1;
                        paswoordDB = user[1];
                    }
                    line = reader.ReadLine();
                }
            }

            // Indien de gebruiker niet bestaat, zal deze if niet uitgevoerd worden
            if (aantalRijen > 0)
            {
                succesGebruiker = true;

                // Paswoord controleren
                succesPaswoord = PaswoordEncryptie.VerifyHash(paswoord, "SHA256", paswoordDB);
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
