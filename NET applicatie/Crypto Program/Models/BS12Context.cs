using System.Data.Entity;
using System.Data.Entity.Infrastructure;
using Crypto_Program.Models.Mapping;

namespace Crypto_Program.Models
{
    public partial class BS12Context : DbContext
    {
        static BS12Context()
        {
            Database.SetInitializer<BS12Context>(null);
        }

        public BS12Context()
            : base("Name=BS12Context")
        {

        }

        public DbSet<Gebruiker> Gebruikers { get; set; }
        public DbSet<GebruikerFile> GebruikerFiles { get; set; }

        protected override void OnModelCreating(DbModelBuilder modelBuilder)
        {
            modelBuilder.Configurations.Add(new GebruikerMap());
            modelBuilder.Configurations.Add(new GebruikerFileMap());
        }
    }
}
