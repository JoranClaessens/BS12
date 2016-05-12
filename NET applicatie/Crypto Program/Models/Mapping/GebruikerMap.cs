using System.ComponentModel.DataAnnotations.Schema;
using System.Data.Entity.ModelConfiguration;

namespace Crypto_Program.Models.Mapping
{
    public class GebruikerMap : EntityTypeConfiguration<Gebruiker>
    {
        //public GebruikerMap()
        //{
        //    // Primary Key
        //    this.HasKey(t => t.Id);

        //    // Properties
        //    this.Property(t => t.Id)
        //        .HasDatabaseGeneratedOption(DatabaseGeneratedOption.None);

        //    this.Property(t => t.Gebruikersnaam)
        //        .HasMaxLength(50);

        //    this.Property(t => t.Paswoord)
        //        .HasMaxLength(250);

        //    this.Property(t => t.Naam)
        //        .HasMaxLength(50);

        //    this.Property(t => t.Voornaam)
        //        .HasMaxLength(50);

        //    // Table & Column Mappings
        //    this.ToTable("Gebruiker");
        //    this.Property(t => t.Id).HasColumnName("Id");
        //    this.Property(t => t.Gebruikersnaam).HasColumnName("Gebruikersnaam");
        //    this.Property(t => t.Paswoord).HasColumnName("Paswoord");
        //    this.Property(t => t.Naam).HasColumnName("Naam");
        //    this.Property(t => t.Voornaam).HasColumnName("Voornaam");
        //}
    }
}
