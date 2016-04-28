
-- --------------------------------------------------
-- Entity Designer DDL Script for SQL Server 2005, 2008, 2012 and Azure
-- --------------------------------------------------
-- Date Created: 04/14/2016 16:10:13
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


-- --------------------------------------------------
-- Dropping existing tables
-- --------------------------------------------------

IF OBJECT_ID(N'[dbo].[Gebruiker]', 'U') IS NOT NULL
    DROP TABLE [dbo].[Gebruiker];
GO

-- --------------------------------------------------
-- Creating all tables
-- --------------------------------------------------

-- Creating table 'Gebruiker'
CREATE TABLE [dbo].[Gebruiker] (
    [Id] int  NOT NULL,
    [Gebruikersnaam] varchar(20)  NOT NULL,
    [Paswoord] varchar(200)  NOT NULL,
    [Naam] nvarchar(max)  NOT NULL,
    [Voornaam] nvarchar(max)  NOT NULL,
    [RSA_Public] nvarchar(max)  NOT NULL
);
GO

-- --------------------------------------------------
-- Creating all PRIMARY KEY constraints
-- --------------------------------------------------

-- Creating primary key on [Id] in table 'Gebruiker'
ALTER TABLE [dbo].[Gebruiker]
ADD CONSTRAINT [PK_Gebruiker]
    PRIMARY KEY CLUSTERED ([Id] ASC);
GO

-- --------------------------------------------------
-- Creating all FOREIGN KEY constraints
-- --------------------------------------------------

-- --------------------------------------------------
-- Script has ended
-- --------------------------------------------------