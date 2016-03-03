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
        public HomeWindow()
        {
            InitializeComponent();

            encryptExpander.MouseEnter += encryptExpander_MouseEnter;
            encryptExpander.MouseLeave += encryptExpander_MouseLeave;

            decryptExpander.MouseEnter += decryptExpander_MouseEnter;
            decryptExpander.MouseLeave += decryptExpander_MouseLeave;
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
    }
}
