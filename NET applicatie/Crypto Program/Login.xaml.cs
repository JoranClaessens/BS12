﻿using System;
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

namespace Crypto_Program
{
    /// <summary>
    /// Interaction logic for Login.xaml
    /// </summary>
    public partial class Login : Window
    {
        public Login()
        {
            InitializeComponent();

            aanmeldButton.Click += aanmeldButton_Click;
            registreerButton.Click += registreerButton_Click;
        }

        void aanmeldButton_Click(object sender, RoutedEventArgs e)
        {
            Home homeWindow = new Home();
            homeWindow.ShowDialog();
        }

        void registreerButton_Click(object sender, RoutedEventArgs e)
        {
            Registreer registreerWindow = new Registreer();
            registreerWindow.ShowDialog();
        }
    }
}
