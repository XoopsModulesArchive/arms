# ==============================================================================
# Table arms_categories added in ArMS 0.3
# ==============================================================================

CREATE TABLE arms_categories (
    cat_id    SMALLINT(3) UNSIGNED DEFAULT "0" NOT NULL AUTO_INCREMENT,
    cat_title VARCHAR(100)         DEFAULT ""  NOT NULL,
    cat_desc  TEXT                 DEFAULT ""  NOT NULL,
    cat_order SMALLINT(3) UNSIGNED DEFAULT ""  NOT NULL,
    PRIMARY KEY (cat_id)
)
    ENGINE = ISAM;

# ==============================================================================
# ArMS 0.3 addons
# ---------------
# cat_id - category id
# sec_image - section image
# ==============================================================================

CREATE TABLE arms_sections (
    sec_id    SMALLINT(3) UNSIGNED DEFAULT "0" NOT NULL AUTO_INCREMENT,
    cat_id    SMALLINT(3) UNSIGNED DEFAULT "0" NOT NULL,
    sec_order SMALLINT(3) UNSIGNED DEFAULT "1" NOT NULL,
    sec_title VARCHAR(100)         DEFAULT ""  NOT NULL,
    sec_desc  TEXT                 DEFAULT ""  NOT NULL,
    sec_image VARCHAR(255)         DEFAULT ""  NOT NULL,
    PRIMARY KEY (sec_id)
)
    ENGINE = ISAM;

CREATE TABLE arms_articals_levels (
    level_id    SMALLINT(3) UNSIGNED DEFAULT "" NOT NULL AUTO_INCREMENT,
    level_name  VARCHAR(100)         DEFAULT "" NOT NULL,
    level_desc  TEXT                 DEFAULT "" NOT NULL,
    level_image VARCHAR(100)         DEFAULT "" NOT NULL,
    PRIMARY KEY (level_id)
)
    ENGINE = ISAM;

# ==============================================================================
# ArMS 0.3 Addons
# ---------------
# art_onhold - while art_onhold is 1 only admin and author can see this artical
# ==============================================================================

CREATE TABLE arms_articals (
    art_id             INT(5) UNSIGNED       DEFAULT "0" NOT NULL AUTO_INCREMENT,
    sec_id             SMALLINT(3) UNSIGNED  DEFAULT "0" NOT NULL,
    level_id           SMALLINT(3) UNSIGNED  DEFAULT "0" NOT NULL,
    uid                INT(5) UNSIGNED       DEFAULT "0" NOT NULL,
    uip                VARCHAR(15)           DEFAULT ""  NOT NULL,

    art_title          VARCHAR(255)          DEFAULT ""  NOT NULL,
    art_desc           TEXT                  DEFAULT ""  NOT NULL,

    art_updatecount    SMALLINT(3) UNSIGNED  DEFAULT "0" NOT NULL,
    art_posttime       INT(11) UNSIGNED      DEFAULT "0" NOT NULL,
    art_lastupdate     INT(11) UNSIGNED      DEFAULT "0" NOT NULL,
    art_lastupdateby   INT(5) UNSIGNED       DEFAULT "0" NOT NULL,
    art_lastupdatebyip VARCHAR(15)           DEFAULT ""  NOT NULL,

    art_ratetotal      MEDIUMINT(8) UNSIGNED DEFAULT "0" NOT NULL,
    art_ratecount      MEDIUMINT(8) UNSIGNED DEFAULT "1" NOT NULL,

    art_views          INT(11) UNSIGNED      DEFAULT "0" NOT NULL,
    art_activated      TINYINT(1)            DEFAULT "0" NOT NULL,
    art_onhold         TINYINT(1)            DEFAULT "1" NOT NULL,
    PRIMARY KEY (art_id)
)
    ENGINE = ISAM;

CREATE TABLE arms_pages (
    page_id             INT(6) UNSIGNED      DEFAULT "0" NOT NULL AUTO_INCREMENT,
    page_order          SMALLINT(3) UNSIGNED DEFAULT "1" NOT NULL,

    art_id              INT(5) UNSIGNED      DEFAULT "0" NOT NULL,
    level_id            SMALLINT(3) UNSIGNED DEFAULT "0" NOT NULL,
    uid                 INT(5) UNSIGNED      DEFAULT "0" NOT NULL,
    uip                 VARCHAR(15)          DEFAULT ""  NOT NULL,

    page_title          VARCHAR(255)         DEFAULT ""  NOT NULL,
    page_desc           TEXT                 DEFAULT ""  NOT NULL,
    page_text           TEXT                 DEFAULT ""  NOT NULL,

    page_allow_html     TINYINT(1)           DEFAULT "0" NOT NULL,
    page_allow_emotions TINYINT(1)           DEFAULT "0" NOT NULL,
    page_allow_bbcode   TINYINT(1)           DEFAULT "1" NOT NULL,

    page_updatecount    SMALLINT(3) UNSIGNED DEFAULT "0" NOT NULL,
    page_posttime       INT(11) UNSIGNED     DEFAULT "0" NOT NULL,
    page_lastupdate     INT(11) UNSIGNED     DEFAULT "0" NOT NULL,
    page_lastupdateby   INT(5) UNSIGNED      DEFAULT "0" NOT NULL,
    page_lastupdatebyip VARCHAR(15)          DEFAULT ""  NOT NULL,

    page_views          INT(11) UNSIGNED     DEFAULT "0" NOT NULL,
    PRIMARY KEY (page_id)
)
    ENGINE = ISAM;

CREATE TABLE arms_votelog (
    vote_id   INT(5) UNSIGNED  DEFAULT "0" NOT NULL AUTO_INCREMENT,
    vote_time INT(11) UNSIGNED DEFAULT "0" NOT NULL,
    art_id    INT(5) UNSIGNED  DEFAULT "0" NOT NULL,
    uid       INT(5) UNSIGNED  DEFAULT "0" NOT NULL,
    uip       VARCHAR(15)      DEFAULT ""  NOT NULL,
    PRIMARY KEY (vote_id)
)
    ENGINE = ISAM;

CREATE TABLE arms_moderators (
    mod_id SMALLINT(3) UNSIGNED DEFAULT "0" NOT NULL AUTO_INCREMENT,
    uid    INT(5) UNSIGNED      DEFAULT "0" NOT NULL,
    sec_id SMALLINT(3) UNSIGNED DEFAULT "0" NOT NULL,
    PRIMARY KEY (mod_id)
)
    ENGINE = ISAM;

# ==============================================================================
# Table arms_permissions added in ArMS 0.3
# Note: fiels can_add_files and can_delete_files not implemented in ArMS 0.3
#       please wait ArMS 0.4 for file attachments...
# ==============================================================================

CREATE TABLE arms_permissions (
    p_id             INT(6) UNSIGNED DEFAULT "0" NOT NULL AUTO_INCREMENT,
    art_id           INT(6) UNSIGNED DEFAULT "0" NOT NULL,
    uid              INT(5) UNSIGNED DEFAULT "0" NOT NULL,
    added_by         INT(5) UNSIGNED DEFAULT "0" NOT NULL,
    can_edit_pages   TINYINT(1)      DEFAULT "1" NOT NULL,
    can_add_pages    TINYINT(1)      DEFAULT "0" NOT NULL,
    can_delete_pages TINYINT(1)      DEFAULT "0" NOT NULL,

    can_add_files    TINYINT(1)      DEFAULT "0" NOT NULL,
    can_delete_files TINYINT(1)      DEFAULT "0" NOT NULL,
    PRIMARY KEY (p_id)
)
    ENGINE = ISAM;

# ==============================================================================
# Table arms_cross_section added in ArMS 0.3
# ==============================================================================

CREATE TABLE arms_cross_section (
    cs_id  INT(9) UNSIGNED      DEFAULT "0" NOT NULL AUTO_INCREMENT,
    art_id INT(6) UNSIGNED      DEFAULT "0" NOT NULL,
    sec_id SMALLINT(3) UNSIGNED DEFAULT "0" NOT NULL,
    PRIMARY KEY (cs_id)
)
    ENGINE = ISAM;
