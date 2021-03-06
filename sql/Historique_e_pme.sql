-- SQL Manager 2008 for SQL Server 3.1.0.4
-- ---------------------------------------
-- Host      : (local)
-- Database  : Historique_e_pme
-- Version   : Microsoft SQL Server  11.0.2100.60


CREATE DATABASE [Historique_e_pme]
GO

USE [Historique_e_pme]
GO

--
-- Definition for table LOG_TRAITEMENT_SAVE : 
--

CREATE TABLE [dbo].[LOG_TRAITEMENT_SAVE] (
  [ID_LG_TR] bigint IDENTITY(1, 1) NOT NULL,
  [ID_UT] bigint NULL,
  [CODE_LG_TR] varchar(255) COLLATE French_CI_AS NULL,
  [ACTION_LG_TR] ntext COLLATE French_CI_AS NULL,
  [LOG_LG_TR] ntext COLLATE French_CI_AS NULL,
  [IP] varchar(19) COLLATE French_CI_AS NULL,
  [BROWER] varchar(150) COLLATE French_CI_AS NULL,
  [SYSTEM] varchar(150) COLLATE French_CI_AS NULL,
  [DATE_INSERT_LG_TR] varchar(19) COLLATE French_CI_AS NULL
)
ON [PRIMARY]
TEXTIMAGE_ON [PRIMARY]
GO

--
-- Definition for table LOG_CONNEXION_SAVE : 
--

CREATE TABLE [dbo].[LOG_CONNEXION_SAVE] (
  [ID_LG_CNX] bigint IDENTITY(1, 1) NOT NULL,
  [ID_UT] bigint NULL,
  [CODE_LG_CNX] varchar(255) COLLATE French_CI_AS NULL,
  [ACTION_LG_CNX] varchar(32) COLLATE French_CI_AS NULL,
  [LOG_LG_CNX] ntext COLLATE French_CI_AS NULL,
  [IP] varchar(19) COLLATE French_CI_AS NULL,
  [BROWER] varchar(150) COLLATE French_CI_AS NULL,
  [SYSTEM] varchar(150) COLLATE French_CI_AS NULL,
  [DATE_INSERT_LG_CNX] varchar(19) COLLATE French_CI_AS NULL,
  [DATE_MODIF_LG_CNX] varchar(19) COLLATE French_CI_AS NULL,
  [ETAT_LG_CNX] smallint NULL
)
ON [PRIMARY]
TEXTIMAGE_ON [PRIMARY]
GO
