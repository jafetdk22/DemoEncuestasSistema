-- Crear la base de datos
CREATE DATABASE Automotriz1;
GO

-- Usar la base de datos creada
USE Automotriz1;
GO

-- Crear la tabla SEORDSER
CREATE TABLE SEORDSER (
    Id INT IDENTITY(1,1) PRIMARY KEY,
    OrMail VARCHAR(255) NOT NULL,
    OrOrden VARCHAR(50) NOT NULL UNIQUE,
    OrAlmCve INT NOT NULL,
    ORFecAlta DATETIME NOT NULL DEFAULT GETDATE(),
    ORNombre VARCHAR(255) NOT NULL,
    ORFecEnt DATETIME NULL,
    ORStatus VARCHAR(20) NOT NULL,
    ORAno INT NULL,
    ORColUni VARCHAR(50) NULL,
    ORChasis VARCHAR(100) NULL,
    ORFalla1 TEXT NULL,
    OPCveOpe INT NOT NULL,
    Modelo VARCHAR(100) NULL,
    FOREIGN KEY (OrAlmCve) REFERENCES GNCATMA(AlmCve),
    FOREIGN KEY (OPCveOpe) REFERENCES NRCATTRA(TraCod)
);
GO

-- Crear la tabla GNCATMA
CREATE TABLE GNCATMA (
    AlmCve INT PRIMARY KEY,
    AlmDsc VARCHAR(255) NOT NULL
);
GO

-- Crear la tabla NRCATTRA
CREATE TABLE NRCATTRA (
    TraCod INT PRIMARY KEY,
    TraNom VARCHAR(255) NOT NULL,
    TraApPat VARCHAR(255) NULL,
    TraApMat VARCHAR(255) NULL
);
GO

-- Insertar datos de prueba en GNCATMA
INSERT INTO GNCATMA (AlmCve, AlmDsc) VALUES (1, 'Almacén Principal'), (2, 'Almacén Secundario');
GO

-- Insertar datos de prueba en NRCATTRA
INSERT INTO NRCATTRA (TraCod, TraNom, TraApPat, TraApMat) VALUES (100, 'Juan', 'Pérez', 'Gómez'), (101, 'Carlos', 'Martínez', 'López');
GO

-- Insertar datos de prueba en SEORDSER
-- Insertar más datos en SEORDSER
INSERT INTO SEORDSER (OrMail, OrOrden, OrAlmCve, ORNombre, ORFecEnt, ORStatus, ORAno, ORColUni, ORChasis, ORFalla1, OPCveOpe, Modelo)
VALUES 
('cliente1@example.com', 'ORD0001', 1, 'Orden Cliente 1', '2025-02-10', 'pendiente', 2022, 'Negro', 'CHS789012', 'Sistema eléctrico fallando', 100, 'Modelo Z'),
('cliente2@example.com', 'ORD0002', 2, 'Orden Cliente 2', '2025-02-11', 'en proceso', 2021, 'Blanco', 'CHS345678', 'Problema de frenos', 101, 'Modelo A'),
('cliente3@example.com', 'ORD0003', 1, 'Orden Cliente 3', '2025-02-12', 'completado', 2020, 'Gris', 'CHS987654', 'Cambio de aceite', 100, 'Modelo B'),
('cliente4@example.com', 'ORD0004', 2, 'Orden Cliente 4', '2025-02-13', 'cancelado', 2019, 'Verde', 'CHS543210', 'Revisión general', 101, 'Modelo C'),
('cliente5@example.com', 'ORD0005', 1, 'Orden Cliente 5', '2025-02-14', 'ce', 2023, 'Azul', 'CHS102938', 'Cambio de llantas', 100, 'Modelo D');
GO

GO

-- Consultas de prueba
SELECT AlmCve, AlmDsc FROM GNCATMA;
GO

SELECT SEO.OPCveOpe, SEO.OrOrden, SEO.OrAlmCve, ORFecAlta, ORNombre, ORFecEnt, TraNom, TraApPat, TraApMat, Modelo, ORAno, ORColUni, ORChasis, ORFalla1, OrAlmCve  
FROM SEORDSER SEO 
INNER JOIN NRCATTRA NR ON SEO.OPCveOpe = NR.TraCod 
WHERE SEO.OrOrden LIKE 'ORD0001' 
AND ORStatus = 'ce';
GO

SELECT OrMail FROM SEORDSER WHERE OrOrden = 'ORD0001';
GO

SELECT * FROM SEORDSER SEO INNER JOIN NRCATTRA NR ON SEO.OPCveOpe = NR.TraCod WHERE OrOrden LIKE'ORD0001'

ALTER TABLE SEORDSER ADD MDes VARCHAR(255) NULL;
UPDATE SEORDSER 
SET MDes = 'Mantenimiento general' 
WHERE OrOrden = '3243242342432';

UPDATE SEORDSER 
SET MDes = 'Cambio de aceite' 
WHERE OrOrden = 'sdasdsadasd';

UPDATE SEORDSER 
SET MDes = 'Sistema eléctrico' 
WHERE OrOrden = 'ORD0001';

UPDATE SEORDSER 
SET MDes = 'Frenos desgastados' 
WHERE OrOrden = 'ORD0002';

UPDATE SEORDSER 
SET MDes = 'Revisión completa' 
WHERE OrOrden = 'ORD0003';

UPDATE SEORDSER 
SET MDes = 'Diagnóstico de motor' 
WHERE OrOrden = 'ORD0004';

UPDATE SEORDSER 
SET MDes = 'Cambio de neumáticos' 
WHERE OrOrden = 'ORD0005';

ALTER TABLE SEORDSER
ADD 
    ORTipOrd VARCHAR(10) NULL,     -- Para el tipo de orden
    ORCliente VARCHAR(100) NULL,    -- Para el cliente
    ORUser VARCHAR(50) NULL,        -- Para el usuario
    ORDirec VARCHAR(255) NULL,      -- Para la dirección
    ORColonia VARCHAR(100) NULL,    -- Para la colonia
    ORCta VARCHAR(50) NULL;         -- Para la cuenta

UPDATE SEORDSER 
SET 
    MDes = 'Sistema eléctrico',
    ORTipOrd = 'Tipo 1',         -- Agrega el valor correspondiente
    ORCliente = 'Cliente A',     -- Agrega el valor correspondiente
    ORUser = 'Usuario 1',       -- Agrega el valor correspondiente
    ORDirec = 'Dirección A',    -- Agrega el valor correspondiente
    ORColonia = 'Colonia A',    -- Agrega el valor correspondiente
    ORCta = 'Cuenta A'          -- Agrega el valor correspondiente
WHERE OrOrden = 'ORD0001';

UPDATE SEORDSER 
SET 
    MDes = 'Frenos desgastados',
    ORTipOrd = 'Tipo 2',         -- Agrega el valor correspondiente
    ORCliente = 'Cliente B',     -- Agrega el valor correspondiente
    ORUser = 'Usuario 2',       -- Agrega el valor correspondiente
    ORDirec = 'Dirección B',    -- Agrega el valor correspondiente
    ORColonia = 'Colonia B',    -- Agrega el valor correspondiente
    ORCta = 'Cuenta B'          -- Agrega el valor correspondiente
WHERE OrOrden = 'ORD0002';

UPDATE SEORDSER 
SET 
    MDes = 'Revisión completa',
    ORTipOrd = 'Tipo 3',         -- Agrega el valor correspondiente
    ORCliente = 'Cliente C',     -- Agrega el valor correspondiente
    ORUser = 'Usuario 3',       -- Agrega el valor correspondiente
    ORDirec = 'Dirección C',    -- Agrega el valor correspondiente
    ORColonia = 'Colonia C',    -- Agrega el valor correspondiente
    ORCta = 'Cuenta C'          -- Agrega el valor correspondiente
WHERE OrOrden = 'ORD0003';

UPDATE SEORDSER 
SET 
    MDes = 'Diagnóstico de motor',
    ORTipOrd = 'Tipo 4',         -- Agrega el valor correspondiente
    ORCliente = 'Cliente D',     -- Agrega el valor correspondiente
    ORUser = 'Usuario 4',       -- Agrega el valor correspondiente
    ORDirec = 'Dirección D',    -- Agrega el valor correspondiente
    ORColonia = 'Colonia D',    -- Agrega el valor correspondiente
    ORCta = 'Cuenta D'          -- Agrega el valor correspondiente
WHERE OrOrden = 'ORD0004';

UPDATE SEORDSER 
SET 
    MDes = 'Cambio de neumáticos',
    ORTipOrd = 'Tipo 5',         -- Agrega el valor correspondiente
    ORCliente = 'Cliente E',     -- Agrega el valor correspondiente
    ORUser = 'Usuario 5',       -- Agrega el valor correspondiente
    ORDirec = 'Dirección E',    -- Agrega el valor correspondiente
    ORColonia = 'Colonia E',    -- Agrega el valor correspondiente
    ORCta = 'Cuenta E'          -- Agrega el valor correspondiente
WHERE OrOrden = 'ORD0005';


SELECT OPCveOpe, OROrden, ORTipOrd, ORCliente, ORNombre, ORUser, ORDirec, 
       ORColonia, ORCta, ORStatus, ORFecEnt, ORFecAlta  
FROM SEORDSER  
WHERE DATEPART(MONTH, ORFecEnt) = 2  
  AND DATEPART(YEAR, ORFecEnt) = 2025  
  AND ORStatus = 'ce'  
  AND ORTipOrd NOT IN ('s', 'i', 'h', 'g');

SELECT NRCATTRA.TraNom, NRCATTRA.TraApPat, NRCATTRA.TraCod FROM  NRCATTRA WHERE TraCod=100

SELECT * FROM SEORDSER SEO INNER JOIN NRCATTRA NR ON SEO.OPCveOpe = NR.TraCod WHERE OrOrden LIKE'ORD0002'