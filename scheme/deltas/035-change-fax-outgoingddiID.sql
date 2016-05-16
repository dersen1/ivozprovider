ALTER TABLE Faxes DROP FOREIGN KEY Faxes_ibfk_2;
ALTER TABLE Faxes DROP KEY outgoingDDI;
ALTER TABLE Faxes CHANGE COLUMN outgoingDDI outgoingDDIId int(10) unsigned DEFAULT NULL;
ALTER TABLE Faxes ADD KEY outgoingDDIId (outgoingDDIId);
ALTER TABLE Faxes ADD FOREIGN KEY `Faxes_ibfk_2` (`outgoingDDIId`) REFERENCES `DDIs` (`id`) ON DELETE SET NULL;