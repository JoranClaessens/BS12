USE [BS12]
GO
/****** Object:  Table [dbo].[Gebruiker]    Script Date: 12/05/2016 10:45:07 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[Gebruiker](
	[Id] [int] NOT NULL,
	[Gebruikersnaam] [varchar](50) NULL,
	[Paswoord] [varchar](250) NULL,
	[Naam] [varchar](50) NULL,
	[Voornaam] [varchar](50) NULL,
 CONSTRAINT [PK_Gebruiker] PRIMARY KEY CLUSTERED 
(
	[Id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[GebruikerFile]    Script Date: 12/05/2016 10:45:07 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[GebruikerFile](
	[FileId] [int] NOT NULL,
	[gebruikersId] [int] NULL,
 CONSTRAINT [PK_GebruikerFile] PRIMARY KEY CLUSTERED 
(
	[FileId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
ALTER TABLE [dbo].[GebruikerFile]  WITH CHECK ADD  CONSTRAINT [FK_GebruikerFile_Gebruiker] FOREIGN KEY([gebruikersId])
REFERENCES [dbo].[Gebruiker] ([Id])
GO
ALTER TABLE [dbo].[GebruikerFile] CHECK CONSTRAINT [FK_GebruikerFile_Gebruiker]
GO
