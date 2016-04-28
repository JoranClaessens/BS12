using System;
using System.Collections.Generic;

namespace Crypto_Program.Models
{
    public partial class GebruikerFile
    {
        public int FileId { get; set; }
        public Nullable<int> gebruikersId { get; set; }
        public virtual Gebruiker Gebruiker { get; set; }
    }
}
