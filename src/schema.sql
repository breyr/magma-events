CREATE TABLE Users (
    username VARCHAR(20) PRIMARY KEY,
    password VARCHAR(20) NOT NULL,
    role ENUM('Admin', 'Server', 'Preparer') NOT NULL,
    firstname VARCHAR(50),
    lastname VARCHAR(50)
);

CREATE TABLE Admins (
    username VARCHAR(20) PRIMARY KEY,
    phone VARCHAR(15) NOT NULL
);

CREATE TABLE Servers (
    username VARCHAR(20) PRIMARY KEY,
    phone VARCHAR(15) NOT NULL,
    rate DECIMAL(5, 2) NOT NULL,
    vacation_days VARCHAR(255)
);

CREATE TABLE Preparers (
    username VARCHAR(20) PRIMARY KEY,
    phone VARCHAR(15) NOT NULL,
    rate DECIMAL(5, 2) NOT NULL,
    vacation_days VARCHAR(255)
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

CREATE TABLE VacationRequests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    vacation_days VARCHAR(255) NOT NULL,
    reason TEXT NOT NULL,
    request_status ENUM('Pending', 'Approved', 'Denied') DEFAULT 'Pending',
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO Users (username, password, role, firstname, lastname) VALUES 
('vc0000', 'M4gm4.Adm1n', 'Admin', 'Vint', 'Cerf'),
('dd2222', 'S3r:v3r5', 'Server', 'David', 'Dobrik'),
('jd5554', 'S3r:v3r5', 'Server', 'Jimmy', 'Donaldson'),
('te8495', 'S3r:v3r5', 'Server', 'Tannar', 'Eacott'),
('fk9008', 'S3r:v3r5', 'Server', 'Felix', 'Kjelberg'),
('lk3392', 'S3r:v3r5', 'Server', 'Liza', 'Koshy'),
('jv0948', 'S3r:v3r5', 'Server', 'James', 'Vietch'),
('jm5445', 'S3r:v3r5', 'Server', 'Jenna', 'Marbles'),
('rm4476', 'S3r:v3r5', 'Server', 'Rhett', 'McLaughlin'),
('jm3245', 'S3r:v3r5', 'Server', 'John', 'Mulaney'),
('ln3333', 'S3r:v3r5', 'Server', 'Link', 'Neal'),
('ls2313', 'S3r:v3r5', 'Server', 'Lindsey', 'Stirling'),
('tt1111', 'S3r:v3r5', 'Server', 'Tyler', 'Toney'),
('cd1323', 'Pr3-p4r3rs', 'Preparer', 'Charli', 'D''Amelio'),
('lg1123', 'Pr3-p4r3rs', 'Preparer', 'Loren', 'Gray'),
('kh0448', 'Pr3-p4r3rs', 'Preparer', 'Kristen', 'Hancher'),
('ch4223', 'Pr3-p4r3rs', 'Preparer', 'Chase', 'Hudson'),
('zk6672', 'Pr3-p4r3rs', 'Preparer', 'Zach', 'King'),
('sk3340', 'Pr3-p4r3rs', 'Preparer', 'Spencer', 'Knight'),
('am8790', 'Pr3-p4r3rs', 'Preparer', 'Ariel', 'Martin'),
('ar3524', 'Pr3-p4r3rs', 'Preparer', 'Addison', 'Rae');

INSERT INTO Admins (username, phone) VALUES 
('vc0000', '212-555-0000');

INSERT INTO Servers (username, phone, rate, vacation_days) VALUES 
('dd2222', '610-555-2222', 11.03, '7/20-7/22'),
('jd5554', '610-555-5463', 9.45, '7/1-7/3'),
('te8495', '484-555-8495', 10.43, '7/16-7/19'),
('fk9008', '215-555-9008', 11.23, '7/2, 7/15, 7/26'),
('lk3392', '212-555-3392', 11.50, '7/6, 7/9, 7/18'),
('jv0948', '609-555-0948', 11.04, '7/21-7/27'),
('jm5445', '484-555-5445', 10.61, '7/15'),
('rm4476', '248-555-4476', 10.22, '7/4 - 7/8'),
('jm3245', '717-555-3245', 9.87, '7/17, 7/19, 7/20'),
('ln3333', '267-555-3333', 9.55, '7/15'),
('ls2313', '215-555-2313', 10.54, NULL),
('tt1111', '215-555-1111', 10.25, '7/1-7/5');

INSERT INTO Preparers (username, phone, rate, vacation_days) VALUES 
('cd1323', '267-555-1323', 15.37, '7/3-7/6'),
('lg1123', '212-555-1123', 15.78, '7/12, 7/16, 7/20'),
('kh0448', '484-555-0448', 15.25, '7/26-7/31'),
('ch4223', '215-555-4223', 16.02, '7/15-7/20'),
('zk6672', '609-555-6672', 14.55, NULL),
('sk3340', '610-555-3340', 15.10, '7/22 - 7/26'),
('am8790', '267-555-8790', 15.44, '7/26'),
('ar3524', '610-555-3524', 14.68, '7/7-7/11');

INSERT INTO Events (event_name, venue_address, venue_phone, organizer_email, date, duration_hours) VALUES 
('Comic-Con @ San Diego Convention Center', '111 W. Harbor Drive', '619-525-5000', 'founder@detroitcomics.com', '2024-07-18', 6.0),
('E3 @ Los Angeles Convention Center', '1202 S. Figueroa Street', '213-741-1151', 'ceo@esa.com', '2024-07-09', 5.0),
('MegaCon @ Orange County Convention Center', '9800 International Drive', '407-685-9800', 'superhero@marvel.com', '2024-07-05', 2.0),
('PAX West @ Washington State Convention Center', '705 Pike Street', '206-694-5000', 'jerryh@pennyarcade.org', '2024-07-20', 3.0),
('Playlist Live @ World Center Mariott', '8701 World Center Drive', '407-239-4200', 'chen@youtube.com', '2024-07-26', 5.0),
('VidCon @ Anaheim Convention Center', '800 W. Katella Avenue', '714-765-8950', 'wmgates@microsoft.com', '2024-07-01', 4.0),
('WOAHX TikTok Conference @ Rio All-Suites Hotel and Casino', '3700 W. Flamingo Road', '619-546-0621', 'founder@tiktok.com', '2024-07-15', 3.5);

INSERT INTO Event_Participants (event_name, username, role) VALUES 
('Comic-Con @ San Diego Convention Center', 'dd2222', 'Server'),
('Comic-Con @ San Diego Convention Center', 'fk9008', 'Server'),
('Comic-Con @ San Diego Convention Center', 'lk3392', 'Server'),
('Comic-Con @ San Diego Convention Center', 'rm4476', 'Server'),
('Comic-Con @ San Diego Convention Center', 'ln3333', 'Server'),
('Comic-Con @ San Diego Convention Center', 'tt1111', 'Server'),
('Comic-Con @ San Diego Convention Center', 'kh0448', 'Preparer'),
('Comic-Con @ San Diego Convention Center', 'am8790', 'Preparer'),
('Comic-Con @ San Diego Convention Center', 'ar3524', 'Preparer'),
('E3 @ Los Angeles Convention Center', 'dd2222', 'Server'),
('E3 @ Los Angeles Convention Center', 'te8495', 'Server'),
('E3 @ Los Angeles Convention Center', 'lk3392', 'Server'),
('E3 @ Los Angeles Convention Center', 'rm4476', 'Server'),
('E3 @ Los Angeles Convention Center', 'ln3333', 'Server'),
('E3 @ Los Angeles Convention Center', 'tt1111', 'Server'),
('E3 @ Los Angeles Convention Center', 'ch4223', 'Preparer'),
('E3 @ Los Angeles Convention Center', 'sk3340', 'Preparer'),
('E3 @ Los Angeles Convention Center', 'am8790', 'Preparer'),
('MegaCon @ Orange County Convention Center', 'jd5554', 'Server'),
('MegaCon @ Orange County Convention Center', 'te8495', 'Server'),
('MegaCon @ Orange County Convention Center', 'jv0948', 'Server'),
('MegaCon @ Orange County Convention Center', 'jm3245', 'Server'),
('MegaCon @ Orange County Convention Center', 'ln3333', 'Server'),
('MegaCon @ Orange County Convention Center', 'ls2313', 'Server'),
('MegaCon @ Orange County Convention Center', 'lg1123', 'Preparer'),
('MegaCon @ Orange County Convention Center', 'kh0448', 'Preparer'),
('MegaCon @ Orange County Convention Center', 'ar3524', 'Preparer'),
('PAX West @ Washington State Convention Center', 'jd5554', 'Server'),
('PAX West @ Washington State Convention Center', 'te8495', 'Server'),
('PAX West @ Washington State Convention Center', 'fk9008', 'Server'),
('PAX West @ Washington State Convention Center', 'lk3392', 'Server'),
('PAX West @ Washington State Convention Center', 'jv0948', 'Server'),
('PAX West @ Washington State Convention Center', 'tt1111', 'Server'),
('PAX West @ Washington State Convention Center', 'kh0448', 'Preparer'),
('PAX West @ Washington State Convention Center', 'sk3340', 'Preparer'),
('PAX West @ Washington State Convention Center', 'am8790', 'Preparer'),
('Playlist Live @ World Center Mariott', 'dd2222', 'Server'),
('Playlist Live @ World Center Mariott', 'te8495', 'Server'),
('Playlist Live @ World Center Mariott', 'lk3392', 'Server'),
('Playlist Live @ World Center Mariott', 'jm3245', 'Server'),
('Playlist Live @ World Center Mariott', 'ls2313', 'Server'),
('Playlist Live @ World Center Mariott', 'tt1111', 'Server'),
('Playlist Live @ World Center Mariott', 'cd1323', 'Preparer'),
('Playlist Live @ World Center Mariott', 'ch4223', 'Preparer'),
('Playlist Live @ World Center Mariott', 'ar3524', 'Preparer'),
('VidCon @ Anaheim Convention Center', 'dd2222', 'Server'),
('VidCon @ Anaheim Convention Center', 'fk9008', 'Server'),
('VidCon @ Anaheim Convention Center', 'lk3392', 'Server'),
('VidCon @ Anaheim Convention Center', 'rm4476', 'Server'),
('VidCon @ Anaheim Convention Center', 'jm3245', 'Server'),
('VidCon @ Anaheim Convention Center', 'ls2313', 'Server'),
('VidCon @ Anaheim Convention Center', 'cd1323', 'Preparer'),
('VidCon @ Anaheim Convention Center', 'ch4223', 'Preparer'),
('VidCon @ Anaheim Convention Center', 'zk6672', 'Preparer'),
('WOAHX TikTok Conference @ Rio All-Suites Hotel and Casino', 'jd5554', 'Server'),
('WOAHX TikTok Conference @ Rio All-Suites Hotel and Casino', 'lk3392', 'Server'),
('WOAHX TikTok Conference @ Rio All-Suites Hotel and Casino', 'jv0948', 'Server'),
('WOAHX TikTok Conference @ Rio All-Suites Hotel and Casino', 'rm4476', 'Server'),
('WOAHX TikTok Conference @ Rio All-Suites Hotel and Casino', 'jm3245', 'Server'),
('WOAHX TikTok Conference @ Rio All-Suites Hotel and Casino', 'ls2313', 'Server'),
('WOAHX TikTok Conference @ Rio All-Suites Hotel and Casino', 'cd1323', 'Preparer'),
('WOAHX TikTok Conference @ Rio All-Suites Hotel and Casino', 'lg1123', 'Preparer'),
('WOAHX TikTok Conference @ Rio All-Suites Hotel and Casino', 'zk6672', 'Preparer');
