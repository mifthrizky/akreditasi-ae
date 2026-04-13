-- CREATE DATABASE
CREATE DATABASE IF NOT EXISTS db_acreditasi;
USE db_acreditasi;

-- CREATE TABLE user
CREATE TABLE IF NOT EXISTS user (
    user_id     INT          PRIMARY KEY AUTO_INCREMENT,
    email       VARCHAR(255) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    role        ENUM('admin','dosen','validator'),
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- CREATE TABLE program_studi
CREATE TABLE IF NOT EXISTS program_studi (
    prodi_id    INT          PRIMARY KEY AUTO_INCREMENT,
    kode        VARCHAR(20)  NOT NULL UNIQUE,
    nama        VARCHAR(255) NOT NULL,
    jurusan     ENUM('ME','FE','DE','AE'),
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- CREATE TABLE user_prodi
CREATE TABLE IF NOT EXISTS user_prodi (
    userprodi_id  INT PRIMARY KEY AUTO_INCREMENT,
    user_id       INT,
    prodi_id      INT,
    FOREIGN KEY (user_id)  REFERENCES user(user_id)                 ON DELETE CASCADE,
    FOREIGN KEY (prodi_id) REFERENCES program_studi(prodi_id)       ON DELETE CASCADE
);

-- CREATE TABLE kriteria
CREATE TABLE IF NOT EXISTS kriteria (
    kriteria_id  INT          PRIMARY KEY AUTO_INCREMENT,
    parent_id    INT          DEFAULT NULL,
    kode         VARCHAR(10)  NOT NULL UNIQUE,
    nama         VARCHAR(255) NOT NULL,
    deskripsi    TEXT,
    level        INT          NOT NULL DEFAULT 0,
    bobot        FLOAT        NOT NULL,
    urutan       INT          NOT NULL,
    CONSTRAINT fk_kriteria_parent
        FOREIGN KEY (parent_id) REFERENCES kriteria(kriteria_id)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- CREATE TABLE template_item
CREATE TABLE IF NOT EXISTS template_item (
    template_id       INT          PRIMARY KEY AUTO_INCREMENT,
    kriteria_id       INT,
    tipe              ENUM('checklist','upload','numerik','narasi'),
    label             VARCHAR(255) NOT NULL,
    hint              VARCHAR(255),
    wajib             BOOLEAN      NOT NULL,
    bobot             FLOAT        NOT NULL,
    nilai_min_numerik FLOAT,
    urutan            INT          NOT NULL,
    FOREIGN KEY (kriteria_id) REFERENCES kriteria(kriteria_id) ON DELETE CASCADE
);

-- CREATE TABLE submission
CREATE TABLE IF NOT EXISTS submission (
    submission_id  INT       PRIMARY KEY AUTO_INCREMENT,
    prodi_id       INT,
    kriteria_id    INT,
    user_id        INT,
    status         ENUM('draft','submitted','diterima','revisi','ditolak'),
    skor           FLOAT,
    submitted_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_submission (prodi_id, kriteria_id),
    FOREIGN KEY (prodi_id)    REFERENCES program_studi(prodi_id) ON DELETE CASCADE,
    FOREIGN KEY (kriteria_id) REFERENCES kriteria(kriteria_id)    ON DELETE CASCADE,
    FOREIGN KEY (user_id)     REFERENCES user(user_id)           ON DELETE CASCADE
);

-- CREATE TABLE submission_item
CREATE TABLE IF NOT EXISTS submission_item (
    subitem_id       INT     PRIMARY KEY AUTO_INCREMENT,
    submission_id    INT,
    template_item_id INT,
    nilai_checklist  BOOLEAN,
    nilai_teks       TEXT,
    nilai_numerik    FLOAT,
    FOREIGN KEY (submission_id)    REFERENCES submission(submission_id)       ON DELETE CASCADE,
    FOREIGN KEY (template_item_id) REFERENCES template_item(template_id)      ON DELETE CASCADE
);

-- CREATE TABLE dokumen
CREATE TABLE IF NOT EXISTS dokumen (
    dokumen_id   INT          PRIMARY KEY AUTO_INCREMENT,
    subitem_id   INT,
    nama_file    VARCHAR(255) NOT NULL,
    path_file    VARCHAR(255) NOT NULL,
    tipe_file    VARCHAR(50)  NOT NULL,
    ukuran_file  INT          NOT NULL,
    uploaded_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (subitem_id) REFERENCES submission_item(subitem_id) ON DELETE CASCADE
);

-- CREATE TABLE validasi
CREATE TABLE IF NOT EXISTS validasi (
    validasi_id    INT       PRIMARY KEY AUTO_INCREMENT,
    submission_id  INT,
    validator_id   INT,
    status         ENUM('disetujui','revisi','ditolak') NOT NULL,
    komentar       TEXT,
    validated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (submission_id) REFERENCES submission(submission_id) ON DELETE CASCADE,
    FOREIGN KEY (validator_id)  REFERENCES user(user_id)            ON DELETE CASCADE
);

-- CREATE TABLE laporan
CREATE TABLE IF NOT EXISTS laporan (
    laporan_id    INT          PRIMARY KEY AUTO_INCREMENT,
    prodi_id      INT,
    generate_by   INT,
    skor_total    FLOAT,
    path_pdf      VARCHAR(255) NOT NULL,
    generated_at  TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (prodi_id)    REFERENCES program_studi(prodi_id) ON DELETE CASCADE,
    FOREIGN KEY (generate_by) REFERENCES user(user_id)           ON DELETE CASCADE
);