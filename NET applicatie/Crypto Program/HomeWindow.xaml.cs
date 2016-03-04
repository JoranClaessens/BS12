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
using System.Windows.Navigation;
using System.Windows.Shapes;

namespace Crypto_Program
{
    /// <summary>
    /// Interaction logic for MainWindow.xaml
    /// </summary>
    public partial class HomeWindow : Window
    {
        public HomeWindow(string gebruiker)
        {
            InitializeComponent();

            encryptExpander.MouseEnter += encryptExpander_MouseEnter;
            encryptExpander.MouseLeave += encryptExpander_MouseLeave;

            decryptExpander.MouseEnter += decryptExpander_MouseEnter;
            decryptExpander.MouseLeave += decryptExpander_MouseLeave;

            gebruikerLabel.Content = "Ingelogd als " + gebruiker;
            afmeldButton.Click += afmeldButton_Click;
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
