-- *********************************************
-- * SQL MySQL generation                      
-- *--------------------------------------------
-- * DB-MAIN version: 11.0.1              
-- * Generator date: Dec  4 2018              
-- * Generation date: Sun Jan 17 10:41:59 2021 
-- * LUN file: C:\Users\angel\Desktop\Università\TechWeb\Milo's Shoe Racks\milos-shoe-racks\DB\KaiyindaHandmade.lun 
-- * Schema: Database/1 
-- ********************************************* 


-- Database Section
-- ________________ 

create database Database;
use Database;


-- Tables Section
-- _____________ 

create table APPARTIENE (
     Nome_Categoria varchar(50) not null,
     Codice_Articolo varchar(10) not null,
     constraint IDAPPARTIENE primary key (Nome_Categoria, Codice_Articolo));

create table ARTICOLO (
     Codice_Articolo varchar(10) not null,
     Data_Inserimento date not null,
     Prezzo decimal(4,2) not null,
     Peso int not null,
     Dimensione varchar(10) not null,
     Scorta int not null,
     Sconto int not null,
     Descrizione varchar(99999) not null,
     Punteggio int not null,
     constraint IDARTICOLO_ID primary key (Codice_Articolo));

create table CARRELLO (
     E-mail varchar(100) not null,
     Codice_Articolo varchar(10) not null,
     Taglia int not null,
     Quantità int not null,
     constraint IDCARRELLO primary key (Codice_Articolo, E-mail));

create table CATEGORIA (
     Nome_Categoria varchar(50) not null,
     Descrizione varchar(99999) not null,
     constraint IDCATEGORIA_ID primary key (Nome_Categoria));

create table DETTAGLIO_ORDINE (
     Data_Ordine date not null,
     Id_Ordine int not null,
     Codice_Articolo varchar(10) not null,
     Taglia char(4) not null,
     Quantità char(3) not null,
     constraint IDORDINATO primary key (Codice_Articolo, Data_Ordine, Id_Ordine));

create table NOTIFICA (
     E-mail varchar(100) not null,
     Id_Notifica char(1) not null,
     Tipo char(1) not null,
     Anteprima_Descrizione char(1) not null,
     Descrizione char(1) not null,
     constraint IDNOTIFICA primary key (E-mail, Id_Notifica));

create table IMMAGINI (
     Codice_Immagine varchar(10) not null,
     Nome_Categoria varchar(50),
     Descrizione varchar(99999) not null,
     Codice_Articolo varchar(10),
     constraint IDIMMAGINI primary key (Codice_Immagine),
     constraint FKRAPPRESENTA_CATEGORIA_ID unique (Nome_Categoria));

create table INDIRIZZO (
     E-mail varchar(100) not null,
     Progressivo int not null,
     Via varchar(100) not null,
     CAP int not null,
     Civico varchar(5) not null,
     Citta varchar(30) not null,
     Provincia char(2) not null,
     constraint IDINDIRIZZO primary key (E-mail, Progressivo));

create table ORDINE (
     Data_Ordine date not null,
     Id_Ordine int not null,
     Data_Consegna date,
     E-mail varchar(100) not null,
     Progressivo int not null,
     constraint IDORDINE primary key (Data_Ordine, Id_Ordine));

create table USER (
     Password varchar(15) not null,
     E-mail varchar(100) not null,
     Admin char not null,
     constraint IDUSER primary key (E-mail));


-- Constraints Section
-- ___________________ 

alter table APPARTIENE add constraint FKAPP_ART
     foreign key (Codice_Articolo)
     references ARTICOLO (Codice_Articolo);

alter table APPARTIENE add constraint FKAPP_CAT
     foreign key (Nome_Categoria)
     references CATEGORIA (Nome_Categoria);

-- Not implemented
-- alter table ARTICOLO add constraint IDARTICOLO_CHK
--     check(exists(select * from APPARTIENE
--                  where APPARTIENE.Codice_Articolo = Codice_Articolo)); 

-- Not implemented
-- alter table ARTICOLO add constraint IDARTICOLO_CHK
--     check(exists(select * from IMMAGINI
--                  where IMMAGINI.Codice_Articolo = Codice_Articolo)); 

alter table CARRELLO add constraint FKCAR_ART
     foreign key (Codice_Articolo)
     references ARTICOLO (Codice_Articolo);

alter table CARRELLO add constraint FKCAR_USE
     foreign key (E-mail)
     references USER (E-mail);

-- Not implemented
-- alter table CATEGORIA add constraint IDCATEGORIA_CHK
--     check(exists(select * from APPARTIENE
--                  where APPARTIENE.Nome_Categoria = Nome_Categoria)); 

alter table DETTAGLIO_ORDINE add constraint FKORD_ART
     foreign key (Codice_Articolo)
     references ARTICOLO (Codice_Articolo);

alter table DETTAGLIO_ORDINE add constraint FKORD_ORD
     foreign key (Data_Ordine, Id_Ordine)
     references ORDINE (Data_Ordine, Id_Ordine);

alter table NOTIFICA add constraint FKA
     foreign key (E-mail)
     references USER (E-mail);

alter table IMMAGINI add constraint FKRAPPRESENTA_ARTICOLO
     foreign key (Codice_Articolo)
     references ARTICOLO (Codice_Articolo);

alter table IMMAGINI add constraint FKRAPPRESENTA_CATEGORIA_FK
     foreign key (Nome_Categoria)
     references CATEGORIA (Nome_Categoria);

alter table INDIRIZZO add constraint FKABITA
     foreign key (E-mail)
     references USER (E-mail);

alter table ORDINE add constraint FKDA CONSEGNARE IN
     foreign key (E-mail, Progressivo)
     references INDIRIZZO (E-mail, Progressivo);


-- Index Section
-- _____________ 

