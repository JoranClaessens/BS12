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

            BS12Entities BS12 = new BS12Entities();

            var user = (from gebruiker in BS12.Gebruikers
                       where gebruiker.Gebruikersnaam == gebruikersNaam
                       select gebruiker).Count();

            if (user > 0)
            {
                existUser = true;
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
                    MessageBox.Show("Ongeldige naam. De gebruikersnaam moet bestaan uit: \n\t - 3 tot 25 karakters \n\t - één hoofdletter \n\t - één klein letter \n" 
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
                MessageBox.Show("Ongeldig paswoord. Het paswoord moet bestaan uit: \n\t - 8 tot 15 karakters \n\t - één hoofdletter \n\t - één klein letter \n\t - één cijfer");
            }

            return isValid;
        }
    }
}
