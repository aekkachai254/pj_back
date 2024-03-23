-- management.ms_accident definition

CREATE TABLE `ms_accident` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `location_latitude` text COLLATE utf8mb4_general_ci,
  `location_longitude` text COLLATE utf8mb4_general_ci,
  `by` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci CHECKSUM=1 COMMENT='แจ้งอุบัติเหตุ';


-- management.ms_admin definition

CREATE TABLE `ms_admin` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `titlename` text NOT NULL,
  `firstname` text NOT NULL,
  `surname` text NOT NULL,
  `register_by` int(1) NOT NULL,
  `register_date` datetime NOT NULL,
  `status_account` int(1) NOT NULL,
  `status_use` int(1) NOT NULL,
  `status_lastlogin` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 CHECKSUM=1 COMMENT='บัญชีผู้ดูแลระบบ';


-- management.ms_car definition

CREATE TABLE `ms_car` (
  `id` char(4) NOT NULL,
  `picture` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `driver` int(1) NOT NULL,
  `brand` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `license` text,
  `color` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `service_life` int(1) NOT NULL,
  `status` int(1) NOT NULL,
  `status_use` int(1) NOT NULL,
  `register_by` int(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 CHECKSUM=1 COMMENT='ข้อมูลรถยนต์';


-- management.ms_personal definition

CREATE TABLE `ms_personal` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `titlename` text NOT NULL,
  `firstname` text NOT NULL,
  `surname` text NOT NULL,
  `register_by` int(1) NOT NULL,
  `register_date` datetime NOT NULL,
  `status_account` int(1) NOT NULL,
  `status_use` int(1) NOT NULL,
  `status_lastlogin` datetime NOT NULL,
  `birthday` date NOT NULL,
  `picture` int(1) NOT NULL,
  `telephone` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `email` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `position` int(1) NOT NULL,
  `sex` int(11) NOT NULL,
  `social_security_id` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `nationality` int(1) NOT NULL,
  `race` int(1) NOT NULL,
  `status_person` int(1) NOT NULL,
  `address` text NOT NULL,
  `id_card` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=100282 DEFAULT CHARSET=utf8 CHECKSUM=1 COMMENT='ข้อมูลบุคลากร';


-- management.ms_product definition

CREATE TABLE `ms_product` (
  `id` char(4) NOT NULL,
  `picture_1` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `picture_2` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `picture_3` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `picture_4` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `name` text,
  `brand` text,
  `formula` text,
  `price` int(6) NOT NULL,
  `contain` int(1) DEFAULT NULL,
  `price_package` int(11) NOT NULL,
  `amount_total` int(1) NOT NULL,
  `amount_balance` int(11) NOT NULL,
  `package` int(11) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `unit` int(1) NOT NULL,
  `shelf` int(1) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 CHECKSUM=1 COMMENT='ข้อมูลสินค้า';


-- management.ms_purchaseorder definition

CREATE TABLE `ms_purchaseorder` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `document_no` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `customer` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `shop` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `date` date NOT NULL,
  `amount_date` date NOT NULL,
  `payment_terms` date NOT NULL,
  `remark` text,
  `price_total` decimal(8,2) NOT NULL,
  `price_discount` int(1) NOT NULL,
  `cost_discount` decimal(8,2) NOT NULL,
  `vate` int(1) NOT NULL,
  `cost_total` decimal(8,2) NOT NULL,
  `cost_text` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `condition_1` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `condition_2` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `condition_3` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `condition_4` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `create_by` int(1) NOT NULL,
  `status_purchaseorder` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 CHECKSUM=1 COMMENT='ข้อมูลใบจัดซื้อ';


-- management.ms_shop definition

CREATE TABLE `ms_shop` (
  `id` int(1) NOT NULL,
  `picture` text,
  `name` text NOT NULL,
  `address` text,
  `location_latitude` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `location_longitude` text,
  `telephone` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 CHECKSUM=1 COMMENT='ข้อมูลร้านค้า';


-- management.ms_student definition

CREATE TABLE `ms_student` (
  `id` char(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `username` text COLLATE utf8mb4_general_ci NOT NULL,
  `password` text COLLATE utf8mb4_general_ci NOT NULL,
  `titlename` int(2) DEFAULT NULL,
  `firstname` text COLLATE utf8mb4_general_ci,
  `surname` text COLLATE utf8mb4_general_ci,
  `student_firstname_eng` text COLLATE utf8mb4_general_ci,
  `student_surname_eng` text COLLATE utf8mb4_general_ci,
  `student_picture` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `card_id` char(13) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `student_nickname` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `student_birthday` date NOT NULL,
  `student_gen` int(3) NOT NULL,
  `class_id` int(1) NOT NULL,
  `level_id` int(2) NOT NULL,
  `room_id` int(2) NOT NULL,
  `student_number` int(2) DEFAULT NULL,
  `student_sex` int(1) NOT NULL,
  `student_religion` int(2) NOT NULL,
  `student_nationality` int(2) NOT NULL,
  `student_race` int(2) NOT NULL,
  `student_address` varchar(10) CHARACTER SET tis620 DEFAULT NULL,
  `student_address_moo` varchar(20) CHARACTER SET tis620 DEFAULT NULL,
  `student_address_soi` varchar(50) CHARACTER SET tis620 DEFAULT NULL,
  `student_address_road` varchar(50) CHARACTER SET tis620 DEFAULT NULL,
  `student_address_tumbol` varchar(50) CHARACTER SET tis620 DEFAULT NULL,
  `student_address_amphur` varchar(50) CHARACTER SET tis620 DEFAULT NULL,
  `student_address_province` varchar(50) CHARACTER SET tis620 DEFAULT NULL,
  `student_address_zip` char(5) CHARACTER SET tis620 DEFAULT NULL,
  `student_tel` char(10) CHARACTER SET tis620 DEFAULT NULL,
  `student_email` text CHARACTER SET tis620,
  `father_status` int(1) NOT NULL,
  `father_firstname` varchar(30) CHARACTER SET tis620 DEFAULT NULL,
  `father_surname` varchar(30) CHARACTER SET tis620 DEFAULT NULL,
  `father_birthday` date NOT NULL,
  `father_occ` int(2) NOT NULL,
  `father_off_add` varchar(100) CHARACTER SET tis620 DEFAULT NULL,
  `father_off_tel` char(10) CHARACTER SET tis620 DEFAULT NULL,
  `father_address` varchar(10) CHARACTER SET tis620 DEFAULT NULL,
  `father_address_moo` varchar(2) CHARACTER SET tis620 DEFAULT NULL,
  `father_address_soi` varchar(50) CHARACTER SET tis620 DEFAULT NULL,
  `father_address_road` varchar(50) CHARACTER SET tis620 DEFAULT NULL,
  `father_address_tumbol` varchar(50) CHARACTER SET tis620 DEFAULT NULL,
  `father_address_amphur` varchar(50) CHARACTER SET tis620 DEFAULT NULL,
  `father_address_province` varchar(50) CHARACTER SET tis620 DEFAULT NULL,
  `father_address_zip` char(5) CHARACTER SET tis620 DEFAULT NULL,
  `father_tel` char(10) CHARACTER SET tis620 DEFAULT NULL,
  `mother_status` int(1) NOT NULL,
  `mother_firstname` varchar(30) CHARACTER SET tis620 DEFAULT NULL,
  `mother_surname` varchar(30) CHARACTER SET tis620 DEFAULT NULL,
  `mother_birthday` date NOT NULL,
  `mother_occ` int(2) NOT NULL,
  `mother_off_add` text CHARACTER SET tis620,
  `mother_off_tel` char(10) CHARACTER SET tis620 DEFAULT NULL,
  `mother_address` varchar(10) CHARACTER SET tis620 DEFAULT NULL,
  `mother_address_moo` varchar(2) CHARACTER SET tis620 DEFAULT NULL,
  `mother_address_soi` varchar(30) CHARACTER SET tis620 DEFAULT NULL,
  `mother_address_road` varchar(30) CHARACTER SET tis620 DEFAULT NULL,
  `mother_address_tumbol` varchar(30) CHARACTER SET tis620 DEFAULT NULL,
  `mother_address_amphur` varchar(30) CHARACTER SET tis620 DEFAULT NULL,
  `mother_address_province` varchar(30) CHARACTER SET tis620 DEFAULT NULL,
  `mother_address_zip` char(5) CHARACTER SET tis620 DEFAULT NULL,
  `mother_tel` char(10) CHARACTER SET tis620 DEFAULT NULL,
  `parents_relation` int(1) NOT NULL,
  `person_livenow` int(2) DEFAULT NULL,
  `marital_status` int(1) NOT NULL,
  `person_firstname` text CHARACTER SET tis620,
  `person_surname` text CHARACTER SET tis620,
  `person_relation` int(1) NOT NULL,
  `person_address` text CHARACTER SET tis620,
  `person_tel` char(10) CHARACTER SET tis620 DEFAULT NULL,
  `friend_firstname_1` text CHARACTER SET tis620,
  `friend_surname_1` text CHARACTER SET tis620,
  `friend_nickname_1` text CHARACTER SET tis620,
  `friend_tel_1` char(10) CHARACTER SET tis620 DEFAULT NULL,
  `friend_level_1` int(2) DEFAULT NULL,
  `friend_room_1` int(2) DEFAULT NULL,
  `friend_firstname_2` text CHARACTER SET tis620,
  `friend_surname_2` text CHARACTER SET tis620,
  `friend_nickname_2` text CHARACTER SET tis620,
  `friend_tel_2` char(10) CHARACTER SET tis620 DEFAULT NULL,
  `friend_level_2` int(2) DEFAULT NULL,
  `friend_room_2` int(2) DEFAULT NULL,
  `study_start_date` date DEFAULT NULL,
  `father_card_id` char(13) CHARACTER SET tis620 DEFAULT NULL,
  `mother_card_id` char(13) CHARACTER SET tis620 DEFAULT NULL,
  `father_religion` int(2) NOT NULL,
  `mother_religion` int(2) NOT NULL,
  `father_nationality` int(2) NOT NULL,
  `mother_nationality` int(2) NOT NULL,
  `father_race` int(2) NOT NULL,
  `mother_race` int(2) NOT NULL,
  `father_position` varchar(50) CHARACTER SET tis620 DEFAULT NULL,
  `mother_position` varchar(50) CHARACTER SET tis620 DEFAULT NULL,
  `study_status` int(2) NOT NULL,
  `student_carno` varchar(5) CHARACTER SET tis620 DEFAULT NULL,
  `student_travel` int(1) NOT NULL,
  `student_weight` float NOT NULL,
  `student_hight` float NOT NULL,
  `student_blood` varchar(5) CHARACTER SET tis620 DEFAULT NULL,
  `student_disease` text CHARACTER SET tis620,
  `student_hobby` text CHARACTER SET tis620,
  `student_skill` text CHARACTER SET tis620,
  `student_facebook` text CHARACTER SET tis620,
  `student_line` text CHARACTER SET tis620,
  `student_instagram` text CHARACTER SET tis620,
  `student_twitter` text CHARACTER SET tis620,
  `congenital_disease` text CHARACTER SET tis620,
  `user_status` int(1) NOT NULL,
  `save_username` varchar(20) CHARACTER SET tis620 DEFAULT NULL,
  `save_date` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `mst_student_x` (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลนักเรียน';


-- management.ms_student_jeck definition

CREATE TABLE `ms_student_jeck` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `username` varchar(75) NOT NULL,
  `password` varchar(255) NOT NULL,
  `titlename` varchar(10) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `birthdate` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `card_id` (`card_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- management.ms_subject definition

CREATE TABLE `ms_subject` (
  `subject_id` varchar(100) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `subject_credit` int(11) NOT NULL,
  `subject_class` int(11) NOT NULL,
  `subject_term` int(11) NOT NULL,
  PRIMARY KEY (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='ข้อมูลนักเรียน';


-- management.ms_system definition

CREATE TABLE `ms_system` (
  `id` int(11) NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `organization` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `version` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `about` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `copyright_design` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `copyright_owner` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `vate` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='ตารางระบบ';


-- management.ms_trip definition

CREATE TABLE `ms_trip` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `date` date NOT NULL,
  `car` char(4) NOT NULL,
  `create_by` int(1) NOT NULL,
  `status_trip` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 CHECKSUM=1 COMMENT='ข้อมูลการเดินทาง';


-- management.tbl_anounce_type definition

CREATE TABLE `tbl_anounce_type` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_general_ci NOT NULL,
  `name_english` text COLLATE utf8mb4_general_ci NOT NULL,
  `tag` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci CHECKSUM=1 COMMENT='ประเภทประกาศ';


-- management.tbl_date definition

CREATE TABLE `tbl_date` (
  `id` char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name_english` char(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='วันที่';


-- management.tbl_day definition

CREATE TABLE `tbl_day` (
  `id` char(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `shortname` char(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='วัน';


-- management.tbl_month definition

CREATE TABLE `tbl_month` (
  `id` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'ตารางเดือน',
  `name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name_english` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='เดือน';


-- management.tbl_nationality definition

CREATE TABLE `tbl_nationality` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET tis620 COLLATE tis620_thai_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nationality_x` (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci CHECKSUM=1 COMMENT='สัญชาติ';


-- management.tbl_package definition

CREATE TABLE `tbl_package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci CHECKSUM=1 COMMENT='บรรจุอย่างเช่น ถุง ห่อ ขวด';


-- management.tbl_position definition

CREATE TABLE `tbl_position` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_general_ci NOT NULL,
  `name_english` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci CHECKSUM=1 COMMENT='ตำแหน่ง';


-- management.tbl_race definition

CREATE TABLE `tbl_race` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET tis620 COLLATE tis620_thai_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nationality_x` (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=256 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci CHECKSUM=1 COMMENT='เชื้อชาติ';


-- management.tbl_religion definition

CREATE TABLE `tbl_religion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci CHECKSUM=1 COMMENT='ศาสนา';


-- management.tbl_sex definition

CREATE TABLE `tbl_sex` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` char(10) CHARACTER SET tis620 COLLATE tis620_thai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tbl_sex_x` (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci CHECKSUM=1 COMMENT='เพศ';


-- management.tbl_shelf definition

CREATE TABLE `tbl_shelf` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name_english` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 CHECKSUM=1 COMMENT='ข้อมูลชั้นวาง';


-- management.tbl_status definition

CREATE TABLE `tbl_status` (
  `id` int(1) NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name_english` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='สถานะการใช้งาน';


-- management.tbl_status_account definition

CREATE TABLE `tbl_status_account` (
  `id` int(11) NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name_english` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='สถานะบัญชี';


-- management.tbl_status_car_use definition

CREATE TABLE `tbl_status_car_use` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name_english` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci CHECKSUM=1 COMMENT='ข้อมูลตำแหน่ง';


-- management.tbl_status_person definition

CREATE TABLE `tbl_status_person` (
  `id` int(1) NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name_english` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='สถานะภาพบุคคล';


-- management.tbl_status_report definition

CREATE TABLE `tbl_status_report` (
  `id` int(11) NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name_english` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='สถานะแจ้งข้อมูลผิดพลาด';


-- management.tbl_status_use definition

CREATE TABLE `tbl_status_use` (
  `id` int(11) NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name_english` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='สถานะการใช้งาน';


-- management.tbl_titlename definition

CREATE TABLE `tbl_titlename` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET tis620 COLLATE tis620_thai_ci,
  `name_english` text CHARACTER SET tis620 COLLATE tis620_thai_ci,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `tbl_ptitle_x` (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 CHECKSUM=1 COMMENT='คำนำหน้าชื่อ';


-- management.tbl_unit definition

CREATE TABLE `tbl_unit` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `name_english` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 CHECKSUM=1 COMMENT='หน่วย';


-- management.tr_anounce definition

CREATE TABLE `tr_anounce` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `type` int(1) DEFAULT NULL,
  `no` int(1) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `detail` text COLLATE utf8mb4_general_ci,
  `by` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci CHECKSUM=1 COMMENT='ประกาศถึงผู้ใช้งาน';


-- management.tr_purchaseorder_detail definition

CREATE TABLE `tr_purchaseorder_detail` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `document_no` text COLLATE utf8mb4_general_ci NOT NULL,
  `product` char(5) COLLATE utf8mb4_general_ci NOT NULL,
  `product_amount` int(1) NOT NULL,
  `status_check` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci CHECKSUM=1 COMMENT='ข้อมูลรายการสินค้าในใบจัดซื้อ';


-- management.tr_report definition

CREATE TABLE `tr_report` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `user_id` text NOT NULL,
  `date` datetime NOT NULL,
  `detail` text NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 CHECKSUM=1 COMMENT='แจ้งข้อมูลผิดพลาด';


-- management.tr_trip_detail definition

CREATE TABLE `tr_trip_detail` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `purchaseorder` int(1) NOT NULL,
  `shop` int(1) NOT NULL,
  `trip` int(1) NOT NULL,
  `status_check` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 CHECKSUM=1 COMMENT='ข้อมูลการเดินทาง';


-- management.grade_student definition

CREATE TABLE `grade_student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(50) NOT NULL,
  `grade` int(11) NOT NULL,
  `term` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`) USING BTREE,
  CONSTRAINT `ID_Student` FOREIGN KEY (`student_id`) REFERENCES `ms_student_jeck` (`card_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- management.subject_grade definition

CREATE TABLE `subject_grade` (
  `subject_id` varchar(100) NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `grade` char(1) NOT NULL,
  `term` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  KEY `Subject_ID` (`subject_id`),
  KEY `Student_ID` (`student_id`),
  CONSTRAINT `Student_ID` FOREIGN KEY (`student_id`) REFERENCES `ms_student_jeck` (`card_id`),
  CONSTRAINT `Subject_ID` FOREIGN KEY (`subject_id`) REFERENCES `ms_subject` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;