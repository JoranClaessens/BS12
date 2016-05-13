
-- --------------------------------------------------
-- Entity Designer DDL Script for SQL Server 2005, 2008, 2012 and Azure
-- --------------------------------------------------
-- Date Created: 05/12/2016 09:40:11
-- Generated from EDMX file: C:\Users\11400919\Desktop\Crypto Program\BS12Model.edmx
-- --------------------------------------------------

SET QUOTED_IDENTIFIER OFF;
GO
USE [BS12];
GO
IF SCHEMA_ID(N'dbo') IS NULL EXECUTE(N'CREATE SCHEMA [dbo]');
GO

-- --------------------------------------------------
-- Dropping existing FOREIGN KEY constraints
-- --------------------------------------------------

IF OBJECT_ID(N'[dbo].[FK_GebruikerFile_Gebruiker]', 'F') IS NOT NULL
    ALTER TABLE [dbo].[GebruikerFile] DROP CONSTRAINT [FK_GebruikerFile_Gebruiker];
GO

-- --------------------------------------------------
-- Dropping existing tables
-- --------------------------------------------------

IF OBJECT_ID(N'[dbo].[Gebruiker]', 'U') IS NOT NULL
    DROP TABLE [dbo].[Gebruiker];
GO
IF OBJECT_ID(N'[dbo].[GebruikerFile]', 'U') IS NOT NULL
    DROP TABLE [dbo].[GebruikerFile];
GO

-- --------------------------------------------------
-- Creating all tables
-- --------------------------------------------------

-- Creating table 'Gebruikers'
CREATE TABLE [dbo].[Gebruikers] (
    [Id] int  NOT NULL,
    [Gebruikersnaam] varchar(50)  NULL,
    [Paswoord] varchar(250)  NULL,
    [Naam] varchar(50)  NULL,
    [Voornaam] varchar(50)  NULL
);
GO

-- Creating table 'GebruikerFiles'
CREATE TABLE [dbo].[GebruikerFiles] (
    [FileId] int  NOT NULL,
    [gebruikersId] int  NULL
);
GO

-- --------------------------------------------------
-- Creating all PRIMARY KEY constraints
-- --------------------------------------------------

-- Creating primary key on [Id] in table 'Gebruikers'
ALTER TABLE [dbo].[Gebruikers]
ADD CONSTRAINT [PK_Gebruikers]
    PRIMARY KEY CLUSTERED ([Id] ASC);
GO

-- Creating primary key on [FileId] in table 'GebruikerFiles'
ALTER TABLE [dbo].[GebruikerFiles]
ADD CONSTRAINT [PK_GebruikerFiles]
    PRIMARY KEY CLUSTERED ([FileId] ASC);
GO

-- --------------------------------------------------
-- Creating all FOREIGN KEY constraints
-- --------------------------------------------------

-- Creating foreign key on [gebruikersId] in table 'GebruikerFiles'
ALTER TABLE [dbo].[GebruikerFiles]
ADD CONSTRAINT [FK_GebruikerFile_Gebruiker]
    FOREIGN KEY ([gebruikersId])
    REFERENCES [dbo].[Gebruikers]
        ([Id])
    ON DELETE NO ACTION ON UPDATE NO ACTION;
GO

-- Creating non-clustered index for FOREIGN KEY 'FK_GebruikerFile_Gebruiker'
CREATE INDEX [IX_FK_GebruikerFile_Gebruiker]
ON [dbo].[GebruikerFiles]
    ([gebruikersId]);
GO

-- --------------------------------------------------
-- Script has ended
-- --------------------------------------------------