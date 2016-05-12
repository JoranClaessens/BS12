using System.ComponentModel.DataAnnotations.Schema;
using System.Data.Entity.ModelConfiguration;

namespace Crypto_Program.Models.Mapping
{
    public class GebruikerFileMap : EntityTypeConfiguration<GebruikerFile>
    {
        //public GebruikerFileMap()
        //{
        //    // Primary Key
        //    this.HasKey(t => t.FileId);

        //    // Properties
        //    this.Property(t => t.FileId)
        //        .HasDatabaseGeneratedOption(DatabaseGeneratedOption.None);

        //    // Table & Column Mappings
        //    this.ToTable("GebruikerFile");
        //    this.Property(t => t.FileId).HasColumnName("FileId");
        //    this.Property(t => t.gebruikersId).HasColumnName("gebruikersId");

        //    // Relationships
        //    this.HasOptional(t => t.Gebruiker)
        //        .WithMany(t => t.GebruikerFiles)
        //        .HasForeignKey(d => d.gebruikersId);

        //}
    }
}
