CREATE TABLE Users (
    username VARCHAR(20) PRIMARY KEY,
    password VARCHAR(20) NOT NULL,
    role ENUM('Admin', 'Server', 'Preparer') NOT NULL
);

CREATE TABLE Admins (
    username VARCHAR(20) PRIMARY KEY,
    phone VARCHAR(15) NOT NULL
);

CREATE TABLE Servers (
    username VARCHAR(20) PRIMARY KEY,
    phone VARCHAR(15) NOT NULL,
    rate DECIMAL(5, 2) NOT NULL,
    vacation_days VARCHAR(50)
);

CREATE TABLE Preparers (
    username VARCHAR(20) PRIMARY KEY,
    phone VARCHAR(15) NOT NULL,
    rate DECIMAL(5, 2) NOT NULL,
    vacation_days VARCHAR(50)
);

CREATE TABLE Events (
    event_name VARCHAR(100) PRIMARY KEY,
    venue_address VARCHAR(255) NOT NULL,
    venue_phone VARCHAR(15) NOT NULL,
    organizer_email VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    duration_hours DECIMAL(4, 1) NOT NULL
);

CREATE TABLE Event_Participants (
    event_name VARCHAR(100),
    username VARCHAR(20),
    role ENUM('Server', 'Preparer'),
    FOREIGN KEY (event_name) REFERENCES Events(event_name),
    FOREIGN KEY (username) REFERENCES Users(username)
);

INSERT INTO Users (username, password, role) VALUES
('vc', 'M4gm4.Adm1n', 'Admin'),
('dd2222', 'S3r:v3r5', 'Server'),
('jd5555', 'S3r:v3r5', 'Server'),
('te9495', 'S3r:v3r5', 'Server'),
('fk9008', 'S3r:v3r5', 'Server'),
('lk2313', 'S3r:v3r5', 'Server'),
('jv0948', 'S3r:v3r5', 'Server'),
('ls2313', 'S3r:v3r5', 'Server'),
('tt1111', 'S3r:v3r5', 'Server'),
('cd1323', 'Pr3-p4r3rs', 'Preparer'),
('lg1123', 'Pr3-p4r3rs', 'Preparer'),
('kh0448', 'Pr3-p4r3rs', 'Preparer'),
('ch4223', 'Pr3-p4r3rs', 'Preparer'),
('zk6672', 'Pr3-p4r3rs', 'Preparer'),
('sk3340', 'Pr3-p4r3rs', 'Preparer'),
('am8790', 'Pr3-p4r3rs', 'Preparer'),
('ar3524', 'Pr3-p4r3rs', 'Preparer');

INSERT INTO Admins (username, phone) VALUES
('vc', '212-555-0000');

INSERT INTO Servers (username, phone, rate, vacation_days) VALUES
('dd2222', '610-555-2222', 11.03, '7/20-7/22'),
('jd5555', '610-555-5463', 9.45, '7/1-7/3'),
('te9495', '484-555-8495', 10.43, '7/16-7/19'),
('fk9008', '215-555-9008', 11.23, '7/2, 7/15, 7/26'),
('lk2313', '215-555-2313', 10.54, ''),
('jv0948', '609-555-0948', 11.04, '7/21-7/27'),
('ls2313', '215-555-2313', 10.54, ''),
('tt1111', '215-555-1111', 10.25, '7/1-7/5');

INSERT INTO Preparers (username, phone, rate, vacation_days) VALUES
('cd1323', '267-555-1323', 15.37, '7/3-7/6'),
('lg1123', '212-555-1123', 15.78, '7/12, 7/16, 7/20'),
('kh0448', '484-555-0448', 15.25, '7/26-7/31'),
('ch4223', '215-555-4223', 16.02, '7/15-7/20'),
('zk6672', '609-555-6672', 14.55, ''),
('sk3340', '610-555-3340', 15.10, '7/22-7/26'),
('am8790', '267-555-8790', 15.44, '7/26'),
('ar3524', '610-555-3524', 14.68, '7/7-7/11');

INSERT INTO Events (event_name, venue_address, venue_phone, organizer_email, date, duration_hours) VALUES
('Comic-Con @ San Diego Convention Center', '111 W. Harbor Drive, San Diego, CA 92101', '619-525-5000', 'founder@detroitcomics.com', '2024-07-18', 6),
('E3 @ Los Angeles Convention Center', '1202 S. Figueroa Street, Los Angeles, CA 90015', '213-741-1151', 'ceo@esa.com', '2024-07-09', 5),
('MegaCon @ Orange County Convention Center', '9800 International Drive, Orlando, FL 32819', '407-685-9800', 'superhero@marvel.com', '2024-07-05', 2),
('PAX West @ Washington State Convention Center', '705 Pike Street, Seattle, WA 98101', '206-694-5000', 'jerryh@pennyarcade.org', '2024-07-20', 3),
('Playlist Live @ World Center Marriott', '8701 World Center Drive, Orlando, FL 32821', '407-239-4200', 'chen@youtube.com', '2024-07-26', 5),
('VidCon @ Anaheim Convention Center', '800 W. Katella Avenue, Anaheim, CA 92802', '714-765-8950', 'wmgates@microsoft.com', '2024-07-01', 4),
('WOAHX TikTok Conference @ Rio All-Suites Hotel and Casino', '3700 W. Flamingo Road, Las Vegas, NV 89103', '619-546-0621', 'founder@tiktok.com', '2024-07-15', 3.5);

INSERT INTO Event_Participants (event_name, username, role) VALUES
('Comic-Con @ San Diego Convention Center', 'dd2222', 'Server'),
('Comic-Con @ San Diego Convention Center', 'fk9008', 'Server'),
('Comic-Con @ San Diego Convention Center', 'jv0948', 'Server'),
('Comic-Con @ San Diego Convention Center', 'ls2313', 'Server'),
('Comic-Con @ San Diego Convention Center', 'lk2313', 'Server'),
('Comic-Con @ San Diego Convention Center', 'tt1111', 'Server'),
('Comic-Con @ San Diego Convention Center', 'kh0448', 'Preparer'),
('Comic-Con @ San Diego Convention Center', 'am8790', 'Preparer');
