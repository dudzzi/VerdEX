CREATE TABLE IF NOT EXISTS plant_catalog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    growing_method TEXT,
    growing_media TEXT,
    fertilizer_info TEXT,
    harvest_time VARCHAR(100),
    care TEXT,
    temperature_range VARCHAR(100),
    humidity_range VARCHAR(100),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS fertilizer_catalog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    nutrient_info TEXT,
    application_info TEXT,
    suitable_plants TEXT,
    notes TEXT,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tool_catalog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    purpose TEXT,
    usage_info TEXT,
    maintenance TEXT,
    notes TEXT,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_type ENUM('plant', 'fertilizer', 'tool') NOT NULL,
    catalog_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    image_path VARCHAR(255),
    stock INT NOT NULL DEFAULT 0,
    unit VARCHAR(30) NOT NULL DEFAULT 'pcs',
    date_added DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_added DATETIME NULL,
    notes TEXT,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_inventory_type (item_type),
    INDEX idx_inventory_catalog (catalog_id)
);

INSERT IGNORE INTO plant_catalog
(name, description, growing_method, growing_media, fertilizer_info, harvest_time, care, temperature_range, humidity_range)
VALUES
('Lettuce',
 'A leafy vegetable commonly grown in hydroponic systems.',
 'NFT or Deep Water Culture (DWC).',
 'Rockwool, coco coir, or another suitable hydroponic medium.',
 'Use a complete hydroponic nutrient solution appropriate for leafy vegetables and follow its label instructions.',
 'Usually about 30–45 days after transplanting, depending on variety and conditions.',
 'Keep the root zone supplied with water and nutrients, provide adequate light, and monitor temperature, humidity, pH, and EC.',
 'About 18–24°C',
 'About 50–70%'),

('Basil',
 'An herb that can grow well in several hydroponic systems.',
 'NFT, DWC, or drip systems.',
 'Rockwool, coco coir, or another suitable hydroponic medium.',
 'Use a complete hydroponic nutrient solution suitable for herbs and follow its label instructions.',
 'Often harvested continuously by trimming mature leaves and shoots; timing varies by variety.',
 'Provide strong light, maintain a healthy root zone, and prune regularly to encourage branching.',
 'About 20–30°C',
 'About 40–70%'),

('Spinach',
 'A leafy green that can be produced in hydroponic systems under suitable conditions.',
 'NFT or DWC.',
 'Rockwool, coco coir, or another suitable hydroponic medium.',
 'Use a complete hydroponic nutrient solution suitable for leafy greens and follow its label instructions.',
 'Often about 35–50 days depending on variety and growing conditions.',
 'Monitor temperature carefully, keep the nutrient solution clean, and provide adequate light and airflow.',
 'About 15–24°C',
 'About 50–70%');

INSERT IGNORE INTO fertilizer_catalog
(name, description, nutrient_info, application_info, suitable_plants, notes)
VALUES
('Hydroponic A/B Nutrient Solution',
 'A two-part nutrient system designed for hydroponic growing.',
 'Provides essential macro- and micronutrients. Exact composition depends on the product.',
 'Mix Part A and Part B separately according to the product label. Do not guess the dosage.',
 'Commonly used for leafy vegetables, herbs, and other hydroponic crops when the formulation is appropriate.',
 'Follow the manufacturer instructions and monitor EC and pH.'),

('Hydroponic Leafy Greens Nutrient',
 'A complete nutrient formulation intended for leafy green hydroponic crops.',
 'Provides nutrients needed for vegetative growth; exact composition varies by product.',
 'Dilute according to the product label and adjust based on the crop and measured EC.',
 'Lettuce, spinach, kale, and similar leafy greens when the product label supports them.',
 'Always follow the actual fertilizer label for dosage and mixing order.');

INSERT IGNORE INTO tool_catalog
(name, description, purpose, usage_info, maintenance, notes)
VALUES
('pH Meter',
 'A digital instrument used to measure the acidity or alkalinity of the nutrient solution.',
 'Checking nutrient-solution pH.',
 'Calibrate and use according to the meter manufacturer instructions.',
 'Keep the probe clean and stored according to the manufacturer instructions.',
 'Calibration solutions and correct storage are important for reliable readings.'),

('EC Meter',
 'A digital instrument used to measure electrical conductivity of a nutrient solution.',
 'Checking nutrient concentration/strength.',
 'Use according to the meter manufacturer instructions and compare readings with the crop or nutrient-product guidance.',
 'Keep the sensor clean and follow the manufacturer storage instructions.',
 'EC is a guide; use the nutrient manufacturer and crop guidance when adjusting nutrients.'),

('Net Pot',
 'A perforated hydroponic container that supports a plant while allowing roots to access the nutrient solution or growing area.',
 'Supporting plants in systems such as NFT or DWC.',
 'Place the plant and suitable growing medium securely inside the net pot.',
 'Clean and sanitize reusable net pots before reuse.',
 'Choose a size compatible with the hydroponic system.');