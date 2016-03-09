using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;

namespace Crypto_Program
{
    class Validatie
    {
        public static bool ValideerGebruiker(string gebruikersNaam)
        {
            bool existUser = false;

            string folder = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData);
            string specificFolder = System.IO.Path.Combine(folder, "CryptoProgram");
            string file = System.IO.Path.Combine(specificFolder, "gebruikers.txt");

            using (StreamReader reader = new StreamReader(file))
            {
                string line = reader.ReadLine();

                while (line != null && !existUser)
                {
                    string[] lines = line.Split(',');
                    if (lines[0] == gebruikersNaam)
                    {
                        existUser = true;
                    }
                    line = reader.ReadLine();
                }
            }

            if (!existUser)
            {
                const int MIN_LENGTH = 3;
                const int MAX_LENGTH = 25;

                if (gebruikersNaam == null)
                {
                    throw new ArgumentNullException();
                }

                bool meetsLengthRequirements = gebruikersNaam.Length >= MIN_LENGTH && gebruikersNaam.Length <= MAX_LENGTH;
                bool hasUpperCaseLetter = false;
                bool hasLowerCaseLetter = false;
                bool hasComma = false;

                if (meetsLengthRequirements)
                {
                    foreach (char c in gebruikersNaam)
                    {
                        if (char.IsUpper(c))
                        {
                            hasUpperCaseLetter = true;
                        }
                        else if (char.IsLower(c))
                        {
                            hasLowerCaseLetter = true;
                        }
                        else if (c == ',')
                        {
                            hasComma = true;
                        }
                    }
                }

                bool isValid = meetsLengthRequirements && hasUpperCaseLetter && hasLowerCaseLetter && !hasComma;

                if (!isValid)
                {
                    MessageBox.Show("Foute naam. De gebruikersnaam moet bestaan uit minstens één hoofdletter en één klein letter." 
                                   + "Komma's worden niet aanvaard.");
                }

                return isValid;
            }
            else
            {
                MessageBox.Show("Deze gebruikersnaam is al in gebruik");
                return false;
            }
        }

        public static bool ValideerPaswoord(string paswoord)
        {
            const int MIN_LENGTH = 8;
            const int MAX_LENGTH = 15;

            if (paswoord == null)
            {
                throw new ArgumentNullException();
            }

            bool meetsLengthRequirements = paswoord.Length >= MIN_LENGTH && paswoord.Length <= MAX_LENGTH;
            bool hasUpperCaseLetter = false;
            bool hasLowerCaseLetter = false;
            bool hasDecimalDigit = false;

            if (meetsLengthRequirements)
            {
                foreach (char c in paswoord)
                {
                    if (char.IsUpper(c))
                    {
                        hasUpperCaseLetter = true;
                    }
                    else if (char.IsLower(c))
                    {
                        hasLowerCaseLetter = true;
                    }
                    else if (char.IsDigit(c))
                    {
                        hasDecimalDigit = true;
                    }
                }
            }

            bool isValid = meetsLengthRequirements && hasUpperCaseLetter && hasLowerCaseLetter && hasDecimalDigit;

            if (!isValid)
            {
                MessageBox.Show("Fout paswoord. Het paswoord moet bestaan uit minstens één hoofdletter, één klein letter en één cijfer.");
            }

            return isValid;
        }
    }
}
