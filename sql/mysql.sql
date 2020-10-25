#### ATTENTION: You don't need to run or use this file!  The install.php script does everything for you!

#
# Table structure for table `attachments`
#

CREATE TABLE {$db_prefix} attachments (
    ID_ATTACH INT(
    10
) UNSIGNED NOT NULL AUTO_INCREMENT,
    ID_THUMB INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MSG INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MEMBER MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    attachmentType TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    filename TINYTEXT NOT NULL,
    size INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    downloads MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    width MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    height MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_ATTACH
),
    UNIQUE ID_MEMBER (
    ID_MEMBER,
    ID_ATTACH
),
    KEY ID_MSG (
    ID_MSG
)
    ) ENGINE = ISAM;

#
# Table structure for table `ban_groups`
#

CREATE TABLE {$db_prefix} ban_groups (
    ID_BAN_GROUP MEDIUMINT(
    8
) UNSIGNED NOT NULL AUTO_INCREMENT,
    NAME VARCHAR (
    20
) NOT NULL DEFAULT '',
    ban_time INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    expire_time INT (
    10
) UNSIGNED,
    cannot_access TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    cannot_register TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    cannot_post TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    cannot_login TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    reason TINYTEXT NOT NULL,
    notes TEXT NOT NULL,
    PRIMARY KEY (
    ID_BAN_GROUP
)
    ) ENGINE = ISAM;

#
# Table structure for table `ban_items`
#

CREATE TABLE {$db_prefix} ban_items (
    ID_BAN MEDIUMINT(
    8
) UNSIGNED NOT NULL AUTO_INCREMENT,
    ID_BAN_GROUP SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    ip_low1 TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    ip_high1 TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    ip_low2 TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    ip_high2 TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    ip_low3 TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    ip_high3 TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    ip_low4 TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    ip_high4 TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    hostname TINYTEXT NOT NULL,
    email_address TINYTEXT NOT NULL,
    ID_MEMBER MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    hits MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_BAN
),
    KEY ID_BAN_GROUP (
    ID_BAN_GROUP
)
    ) ENGINE = ISAM;

#
# Table structure for table `board_permissions`
#

CREATE TABLE {$db_prefix} board_permissions (
    ID_GROUP SMALLINT(
    5
) NOT NULL DEFAULT '0',
    ID_BOARD SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    permission VARCHAR (
    30
) NOT NULL DEFAULT '',
    addDeny TINYINT (
    4
) NOT NULL DEFAULT '1',
    PRIMARY KEY (
    ID_GROUP,
    ID_BOARD,
    permission
)
    ) ENGINE = ISAM;

#
# Dumping data for table `board_permissions`
#

INSERT INTO {$db_prefix} board_permissions
    (ID_GROUP, ID_BOARD, permission)
VALUES (-1, 0, 'poll_view'),
    (0, 0, 'remove_own'),
    (0, 0, 'lock_own'),
    (0, 0, 'mark_any_notify'),
    (0, 0, 'mark_notify'),
    (0, 0, 'modify_own'),
    (0, 0, 'poll_add_own'),
    (0, 0, 'poll_edit_own'),
    (0, 0, 'poll_lock_own'),
    (0, 0, 'poll_post'),
    (0, 0, 'poll_view'),
    (0, 0, 'poll_vote'),
    (0, 0, 'post_attachment'),
    (0, 0, 'post_new'),
    (0, 0, 'post_reply_any'),
    (0, 0, 'post_reply_own'),
    (0, 0, 'delete_own'),
    (0, 0, 'report_any'),
    (0, 0, 'send_topic'),
    (0, 0, 'view_attachments'),
    (2, 0, 'moderate_board'),
    (2, 0, 'post_new'),
    (2, 0, 'post_reply_own'),
    (2, 0, 'post_reply_any'),
    (2, 0, 'poll_post'),
    (2, 0, 'poll_add_any'),
    (2, 0, 'poll_remove_any'),
    (2, 0, 'poll_view'),
    (2, 0, 'poll_vote'),
    (2, 0, 'poll_edit_any'),
    (2, 0, 'report_any'),
    (2, 0, 'lock_own'),
    (2, 0, 'send_topic'),
    (2, 0, 'mark_any_notify'),
    (2, 0, 'mark_notify'),
    (2, 0, 'delete_own'),
    (2, 0, 'modify_own'),
    (2, 0, 'make_sticky'),
    (2, 0, 'lock_any'),
    (2, 0, 'remove_any'),
    (2, 0, 'move_any'),
    (2, 0, 'merge_any'),
    (2, 0, 'split_any'),
    (2, 0, 'delete_any'),
    (2, 0, 'modify_any'),
    (3, 0, 'moderate_board'),
    (3, 0, 'post_new'),
    (3, 0, 'post_reply_own'),
    (3, 0, 'post_reply_any'),
    (3, 0, 'poll_post'),
    (3, 0, 'poll_add_own'),
    (3, 0, 'poll_remove_any'),
    (3, 0, 'poll_view'),
    (3, 0, 'poll_vote'),
    (3, 0, 'report_any'),
    (3, 0, 'lock_own'),
    (3, 0, 'send_topic'),
    (3, 0, 'mark_any_notify'),
    (3, 0, 'mark_notify'),
    (3, 0, 'delete_own'),
    (3, 0, 'modify_own'),
    (3, 0, 'make_sticky'),
    (3, 0, 'lock_any'),
    (3, 0, 'remove_any'),
    (3, 0, 'move_any'),
    (3, 0, 'merge_any'),
    (3, 0, 'split_any'),
    (3, 0, 'delete_any'),
    (3, 0, 'modify_any');
# --------------------------------------------------------

#
# Table structure for table `boards`
#

CREATE TABLE {$db_prefix} boards (
    ID_BOARD SMALLINT(
    5
) UNSIGNED NOT NULL AUTO_INCREMENT,
    ID_CAT TINYINT (
    4
) UNSIGNED NOT NULL DEFAULT '0',
    childLevel TINYINT (
    4
) UNSIGNED NOT NULL DEFAULT '0',
    ID_PARENT SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    boardOrder SMALLINT (
    5
) NOT NULL DEFAULT '0',
    ID_LAST_MSG INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MSG_UPDATED INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    memberGroups VARCHAR (
    255
) NOT NULL DEFAULT '-1,0',
    NAME TINYTEXT NOT NULL,
    DESCRIPTION TEXT NOT NULL,
    numTopics MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    numPosts MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    countPosts TINYINT (
    4
) NOT NULL DEFAULT '0',
    ID_THEME TINYINT (
    4
) UNSIGNED NOT NULL DEFAULT '0',
    permission_mode TINYINT (
    4
) UNSIGNED NOT NULL DEFAULT '0',
    override_theme TINYINT (
    4
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_BOARD
),
    UNIQUE categories (
    ID_CAT,
    ID_BOARD
),
    KEY ID_PARENT (
    ID_PARENT
),
    KEY ID_MSG_UPDATED (
    ID_MSG_UPDATED
),
    KEY memberGroups (
    memberGroups(
    48
))
    ) ENGINE = ISAM;

#
# Dumping data for table `boards`
#

INSERT INTO {$db_prefix} boards
    (ID_BOARD, ID_CAT, boardOrder, ID_LAST_MSG, ID_MSG_UPDATED, NAME, DESCRIPTION, numTopics, numPosts, memberGroups)
VALUES (1, 1, 1, 1, 1, '{$default_board_name}', '{$default_board_description}', 1, 1, '-1,0');
# --------------------------------------------------------

#
# Table structure for table `calendar`
#

CREATE TABLE {$db_prefix} calendar (
    ID_EVENT SMALLINT(
    5
) UNSIGNED NOT NULL AUTO_INCREMENT,
    startDate DATE NOT NULL DEFAULT '0001-01-01',
    endDate DATE NOT NULL DEFAULT '0001-01-01',
    ID_BOARD SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    ID_TOPIC MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    title VARCHAR (
    48
) NOT NULL DEFAULT '',
    ID_MEMBER MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_EVENT
),
    KEY startDate (
    startDate
),
    KEY endDate (
    endDate
),
    KEY topic (
    ID_TOPIC,
    ID_MEMBER
)
    ) ENGINE = ISAM;

#
# Table structure for table `calendar_holidays`
#

CREATE TABLE {$db_prefix} calendar_holidays (
    ID_HOLIDAY SMALLINT(
    5
) UNSIGNED NOT NULL AUTO_INCREMENT,
    eventDate DATE NOT NULL DEFAULT '0001-01-01',
    title VARCHAR (
    30
) NOT NULL DEFAULT '',
    PRIMARY KEY (
    ID_HOLIDAY
),
    KEY eventDate (
    eventDate
)
    ) ENGINE = ISAM;


# --------------------------------------------------------

#
# Table structure for table `categories`
#

CREATE TABLE {$db_prefix} categories (
    ID_CAT TINYINT(
    4
) UNSIGNED NOT NULL AUTO_INCREMENT,
    catOrder TINYINT (
    4
) NOT NULL DEFAULT '0',
    NAME TINYTEXT NOT NULL,
    canCollapse TINYINT (
    1
) NOT NULL DEFAULT '1',
    PRIMARY KEY (
    ID_CAT
)
    ) ENGINE = ISAM;

#
# Dumping data for table `categories`
#

INSERT INTO {$db_prefix} categories
VALUES (1, 0, '{$default_category_name}', 1);
# --------------------------------------------------------

#
# Table structure for table `collapsed_categories`
#

CREATE TABLE {$db_prefix} collapsed_categories (
    ID_CAT TINYINT(
    4
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MEMBER MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_CAT,
    ID_MEMBER
)
    ) ENGINE = ISAM;

#
# Table structure for table `log_actions`
#

CREATE TABLE {$db_prefix} log_actions (
    ID_ACTION INT(
    10
) UNSIGNED NOT NULL AUTO_INCREMENT,
    logTime INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MEMBER MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ip CHAR (
    16
) NOT NULL DEFAULT '                ',
    ACTION VARCHAR (
    30
) NOT NULL DEFAULT '',
    extra TEXT NOT NULL,
    PRIMARY KEY (
    ID_ACTION
),
    KEY logTime (
    logTime
),
    KEY ID_MEMBER (
    ID_MEMBER
)
    ) ENGINE = ISAM;

#
# Table structure for table `log_activity`
#

CREATE TABLE {$db_prefix} log_activity (
    DATE
    DATE
    NOT
    NULL
    DEFAULT
    '0001-01-01',
    hits
    MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    topics SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    posts SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    registers SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    mostOn SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    DATE
),
    KEY hits (
    hits
),
    KEY mostOn (
    mostOn
)
    ) ENGINE = ISAM;

#
# Table structure for table `log_banned`
#

CREATE TABLE {$db_prefix} log_banned (
    ID_BAN_LOG MEDIUMINT(
    8
) UNSIGNED NOT NULL AUTO_INCREMENT,
    ID_MEMBER MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ip CHAR (
    16
) NOT NULL DEFAULT '                ',
    email TINYTEXT NOT NULL,
    logTime INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_BAN_LOG
),
    KEY logTime (
    logTime
)
    ) ENGINE = ISAM;

#
# Table structure for table `log_boards`
#

CREATE TABLE {$db_prefix} log_boards (
    ID_MEMBER MEDIUMINT(
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ID_BOARD SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MSG INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_MEMBER,
    ID_BOARD
)
    ) ENGINE = ISAM;

#
# Table structure for table `log_errors`
#

CREATE TABLE {$db_prefix} log_errors (
    ID_ERROR MEDIUMINT(
    8
) UNSIGNED NOT NULL AUTO_INCREMENT,
    logTime INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MEMBER MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ip CHAR (
    16
) NOT NULL DEFAULT '                ',
    url TEXT NOT NULL,
    message TEXT NOT NULL,
    SESSION CHAR (
    32
) NOT NULL DEFAULT '                                ',
    PRIMARY KEY (
    ID_ERROR
),
    KEY logTime (
    logTime
),
    KEY ID_MEMBER (
    ID_MEMBER
),
    KEY ip (
    ip(
    16
))
    ) ENGINE = ISAM;

#
# Table structure for table `log_floodcontrol`
#

CREATE TABLE {$db_prefix} log_floodcontrol (
    ip CHAR(
    16
) NOT NULL DEFAULT '                ',
    logTime INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ip(
    16
))
    ) ENGINE = ISAM;

#
# Table structure for table `log_karma`
#

CREATE TABLE {$db_prefix} log_karma (
    ID_TARGET MEDIUMINT(
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ID_EXECUTOR MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    logTime INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    ACTION TINYINT (
    4
) NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_TARGET,
    ID_EXECUTOR
),
    KEY logTime (
    logTime
)
    ) ENGINE = ISAM;

#
# Table structure for table `log_mark_read`
#

CREATE TABLE {$db_prefix} log_mark_read (
    ID_MEMBER MEDIUMINT(
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ID_BOARD SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MSG INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_MEMBER,
    ID_BOARD
)
    ) ENGINE = ISAM;

#
# Table structure for table `log_notify`
#

CREATE TABLE {$db_prefix} log_notify (
    ID_MEMBER MEDIUMINT(
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ID_TOPIC MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ID_BOARD SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    sent TINYINT (
    1
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_MEMBER,
    ID_TOPIC,
    ID_BOARD
)
    ) ENGINE = ISAM;

#
# Table structure for table `log_online`
#

CREATE TABLE {$db_prefix} log_online (
    SESSION VARCHAR(
    32
) NOT NULL DEFAULT '',
    logTime TIMESTAMP (
    14
) /*!40102 NOT NULL DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP */,
    ID_MEMBER MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ip INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    url TEXT NOT NULL,
    PRIMARY KEY (
    SESSION
),
    KEY logTime (
    logTime
),
    KEY ID_MEMBER (
    ID_MEMBER
)
    ) ENGINE = ISAM;

#
# Table structure for table `log_polls`
#

CREATE TABLE {$db_prefix} log_polls (
    ID_POLL MEDIUMINT(
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MEMBER MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ID_CHOICE TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_POLL,
    ID_MEMBER,
    ID_CHOICE
)
    ) ENGINE = ISAM;

#
# Table structure for table `log_search_messages`
#

CREATE TABLE {$db_prefix} log_search_messages (
    ID_SEARCH TINYINT(
    3
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MSG INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_SEARCH,
    ID_MSG
)
    ) ENGINE = ISAM;

#
# Table structure for table `log_search_results`
#

CREATE TABLE {$db_prefix} log_search_results (
    ID_SEARCH TINYINT(
    3
) UNSIGNED NOT NULL DEFAULT '0',
    ID_TOPIC MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MSG INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    relevance SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    num_matches SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_SEARCH,
    ID_TOPIC
)
    ) ENGINE = ISAM;

#
# Table structure for table `log_search_subjects`
#

CREATE TABLE {$db_prefix} log_search_subjects (
    word VARCHAR(
    20
) NOT NULL DEFAULT '',
    ID_TOPIC MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    word,
    ID_TOPIC
),
    KEY ID_TOPIC (
    ID_TOPIC
)
    ) ENGINE = ISAM;

#
# Table structure for table `log_search_topics`
#

CREATE TABLE {$db_prefix} log_search_topics (
    ID_SEARCH TINYINT(
    3
) UNSIGNED NOT NULL DEFAULT '0',
    ID_TOPIC MEDIUMINT (
    9
) NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_SEARCH,
    ID_TOPIC
)
    ) ENGINE = ISAM;

#
# Table structure for table `log_topics`
#

CREATE TABLE {$db_prefix} log_topics (
    ID_MEMBER MEDIUMINT(
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ID_TOPIC MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MSG INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_MEMBER,
    ID_TOPIC
),
    KEY ID_TOPIC (
    ID_TOPIC
)
    ) ENGINE = ISAM;

#
# Table structure for table `membergroups`
#

CREATE TABLE {$db_prefix} membergroups (
    ID_GROUP SMALLINT(
    5
) UNSIGNED NOT NULL AUTO_INCREMENT,
    groupName VARCHAR (
    80
) NOT NULL DEFAULT '',
    onlineColor VARCHAR (
    20
) NOT NULL DEFAULT '',
    minPosts MEDIUMINT (
    9
) NOT NULL DEFAULT '-1',
    maxMessages SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    stars TINYTEXT NOT NULL,
    PRIMARY KEY (
    ID_GROUP
),
    KEY minPosts (
    minPosts
)
    ) ENGINE = ISAM;

#
# Dumping data for table `membergroups`
#

INSERT INTO {$db_prefix} membergroups
    (ID_GROUP, groupName, onlineColor, minPosts, stars)
VALUES (1, '{$default_administrator_group}', '#FF0000', -1, '5#staradmin.gif'),
    (2, '{$default_global_moderator_group}', '#0000FF', -1, '5#stargmod.gif'),
    (3, '{$default_moderator_group}', '', -1, '5#starmod.gif'),
    (4, '{$default_newbie_group}', '', 0, '1#star.gif'),
    (5, '{$default_junior_group}', '', 50, '2#star.gif'),
    (6, '{$default_full_group}', '', 100, '3#star.gif'),
    (7, '{$default_senior_group}', '', 250, '4#star.gif'),
    (8, '{$default_hero_group}', '', 500, '5#star.gif');
# --------------------------------------------------------


#
# Table structure for table `message_icons`
#

CREATE TABLE {$db_prefix} message_icons (
    ID_ICON SMALLINT(
    5
) UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR (
    80
) NOT NULL DEFAULT '',
    filename VARCHAR (
    80
) NOT NULL DEFAULT '',
    ID_BOARD MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT 0,
    iconOrder SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (
    ID_ICON
),
    KEY ID_BOARD (
    ID_BOARD
)
    ) ENGINE = ISAM;

#
# Dumping data for table `message_icons`
#

# // !!! i18n
INSERT INTO {$db_prefix} message_icons
    (filename, title, iconOrder)
VALUES ('xx', 'Standard', '0'),
    ('thumbup', 'Thumb Up', '1'),
    ('thumbdown', 'Thumb Down', '2'),
    ('exclamation', 'Exclamation point', '3'),
    ('question', 'Question mark', '4'),
    ('lamp', 'Lamp', '5'),
    ('smiley', 'Smiley', '6'),
    ('angry', 'Angry', '7'),
    ('cheesy', 'Cheesy', '8'),
    ('grin', 'Grin', '9'),
    ('sad', 'Sad', '10'),
    ('wink', 'Wink', '11');
# --------------------------------------------------------

#
# Table structure for table `messages`
#

CREATE TABLE {$db_prefix} messages (
    ID_MSG INT(
    10
) UNSIGNED NOT NULL AUTO_INCREMENT,
    ID_TOPIC MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ID_BOARD SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    posterTime INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MEMBER MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MSG_MODIFIED INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    SUBJECT TINYTEXT NOT NULL,
    posterName TINYTEXT NOT NULL,
    posterEmail TINYTEXT NOT NULL,
    posterIP TINYTEXT NOT NULL,
    smileysEnabled TINYINT (
    4
) NOT NULL DEFAULT '1',
    modifiedTime INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    modifiedName TINYTEXT NOT NULL,
    BODY TEXT NOT NULL,
    icon VARCHAR (
    16
) NOT NULL DEFAULT 'xx',
    PRIMARY KEY (
    ID_MSG
),
    UNIQUE topic (
    ID_TOPIC,
    ID_MSG
),
    UNIQUE ID_BOARD (
    ID_BOARD,
    ID_MSG
),
    UNIQUE ID_MEMBER (
    ID_MEMBER,
    ID_MSG
),
    KEY ipIndex (
    posterIP(
    15
), ID_TOPIC),
    KEY participation (
    ID_MEMBER,
    ID_TOPIC
),
    KEY showPosts (
    ID_MEMBER,
    ID_BOARD
),
    KEY ID_TOPIC (
    ID_TOPIC
)
    ) ENGINE = ISAM;

#
# Dumping data for table `messages`
#

INSERT INTO {$db_prefix} messages
    (ID_MSG, ID_MSG_MODIFIED, ID_TOPIC, ID_BOARD, posterTime, SUBJECT, posterName, posterEmail, posterIP, modifiedName, BODY, icon)
VALUES (1, 1, 1, 1, UNIX_TIMESTAMP(), '{$default_topic_subject}', 'Simple Machines', 'info@simplemachines.org', '127.0.0.1', '', '{$default_topic_message}', 'xx');
# --------------------------------------------------------

#
# Table structure for table `moderators`
#

CREATE TABLE {$db_prefix} moderators (
    ID_BOARD SMALLINT(
    5
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MEMBER MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_BOARD,
    ID_MEMBER
)
    ) ENGINE = ISAM;

#
# Table structure for table `package_servers`
#

CREATE TABLE {$db_prefix} package_servers (
    ID_SERVER SMALLINT(
    5
) UNSIGNED NOT NULL AUTO_INCREMENT,
    NAME TINYTEXT NOT NULL,
    url TINYTEXT NOT NULL,
    PRIMARY KEY (
    ID_SERVER
)
    ) ENGINE = ISAM;

#
# Dumping data for table `package_servers`
#

INSERT INTO {$db_prefix} package_servers
    (NAME, url)
VALUES ('Simple Machines Third-party Mod Site', 'http://mods.simplemachines.org');
# --------------------------------------------------------

#
# Table structure for table `permissions`
#

CREATE TABLE {$db_prefix} permissions (
    ID_GROUP SMALLINT(
    5
) NOT NULL DEFAULT '0',
    permission VARCHAR (
    30
) NOT NULL DEFAULT '',
    addDeny TINYINT (
    4
) NOT NULL DEFAULT '1',
    PRIMARY KEY (
    ID_GROUP,
    permission
)
    ) ENGINE = ISAM;

#
# Dumping data for table `permissions`
#

INSERT INTO {$db_prefix} permissions
    (ID_GROUP, permission)
VALUES (-1, 'search_posts'),
    (-1, 'calendar_view'),
    (-1, 'view_stats'),
    (-1, 'profile_view_any'),
    (0, 'view_mlist'),
    (0, 'search_posts'),
    (0, 'profile_view_own'),
    (0, 'profile_view_any'),
    (0, 'pm_read'),
    (0, 'pm_send'),
    (0, 'calendar_view'),
    (0, 'view_stats'),
    (0, 'who_view'),
    (0, 'profile_identity_own'),
    (0, 'profile_extra_own'),
    (0, 'profile_remove_own'),
    (0, 'profile_server_avatar'),
    (0, 'profile_upload_avatar'),
    (0, 'profile_remote_avatar'),
    (0, 'karma_edit'),
    (2, 'view_mlist'),
    (2, 'search_posts'),
    (2, 'profile_view_own'),
    (2, 'profile_view_any'),
    (2, 'pm_read'),
    (2, 'pm_send'),
    (2, 'calendar_view'),
    (2, 'view_stats'),
    (2, 'who_view'),
    (2, 'profile_identity_own'),
    (2, 'profile_extra_own'),
    (2, 'profile_remove_own'),
    (2, 'profile_server_avatar'),
    (2, 'profile_upload_avatar'),
    (2, 'profile_remote_avatar'),
    (2, 'profile_title_own'),
    (2, 'calendar_post'),
    (2, 'calendar_edit_any'),
    (2, 'karma_edit');
# --------------------------------------------------------

#
# Table structure for table `personal_messages`
#

CREATE TABLE {$db_prefix} personal_messages (
    ID_PM INT(
    10
) UNSIGNED NOT NULL AUTO_INCREMENT,
    ID_MEMBER_FROM MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    deletedBySender TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    fromName TINYTEXT NOT NULL,
    msgtime INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    SUBJECT TINYTEXT NOT NULL,
    BODY TEXT NOT NULL,
    PRIMARY KEY (
    ID_PM
),
    KEY ID_MEMBER (
    ID_MEMBER_FROM,
    deletedBySender
),
    KEY msgtime (
    msgtime
)
    ) ENGINE = ISAM;

#
# Table structure for table `pm_recipients`
#

CREATE TABLE {$db_prefix} pm_recipients (
    ID_PM INT(
    10
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MEMBER MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    labels VARCHAR (
    60
) NOT NULL DEFAULT '-1',
    bcc TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    is_read TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    deleted TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_PM,
    ID_MEMBER
),
    UNIQUE ID_MEMBER (
    ID_MEMBER,
    deleted,
    ID_PM
)
    ) ENGINE = ISAM;

#
# Table structure for table `polls`
#

CREATE TABLE {$db_prefix} polls (
    ID_POLL MEDIUMINT(
    8
) UNSIGNED NOT NULL AUTO_INCREMENT,
    question TINYTEXT NOT NULL,
    votingLocked TINYINT (
    1
) NOT NULL DEFAULT '0',
    maxVotes TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '1',
    expireTime INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    hideResults TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    changeVote TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MEMBER MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    posterName TINYTEXT NOT NULL,
    PRIMARY KEY (
    ID_POLL
)
    ) ENGINE = ISAM;

#
# Table structure for table `poll_choices`
#

CREATE TABLE {$db_prefix} poll_choices (
    ID_POLL MEDIUMINT(
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ID_CHOICE TINYINT (
    3
) UNSIGNED NOT NULL DEFAULT '0',
    label TINYTEXT NOT NULL,
    votes SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_POLL,
    ID_CHOICE
)
    ) ENGINE = ISAM;

#
# Table structure for table `settings`
#

CREATE TABLE {$db_prefix} settings (
    variable
    TINYTEXT
    NOT
    NULL,
    VALUE
    TEXT
    NOT
    NULL,
    PRIMARY
    KEY (
    variable(
    30
))
    ) ENGINE = ISAM;

#
# Dumping data for table `settings`
#

INSERT INTO {$db_prefix} settings
    (variable, VALUE)
VALUES ('smfVersion', '{$smf_version}'),
    ('news', '{$default_news}'),
    ('compactTopicPagesContiguous', '5'),
    ('compactTopicPagesEnable', '1'),
    ('enableStickyTopics', '1'),
    ('todayMod', '1'),
    ('karmaMode', '0'),
    ('karmaTimeRestrictAdmins', '1'),
    ('enablePreviousNext', '1'),
    ('pollMode', '1'),
    ('enableVBStyleLogin', '1'),
    ('enableCompressedOutput', '{$enableCompressedOutput}'),
    ('karmaWaitTime', '1'),
    ('karmaMinPosts', '0'),
    ('karmaLabel', '{$default_karmaLabel}'),
    ('karmaSmiteLabel', '{$default_karmaSmiteLabel}'),
    ('karmaApplaudLabel', '{$default_karmaApplaudLabel}'),
    ('attachmentSizeLimit', '128'),
    ('attachmentPostLimit', '192'),
    ('attachmentNumPerPostLimit', '4'),
    ('attachmentDirSizeLimit', '10240'),
    ('attachmentUploadDir', '{$boarddir}/attachments'),
    ('attachmentExtensions', 'doc,gif,jpg,mpg,pdf,png,txt,zip'),
    ('attachmentCheckExtensions', '0'),
    ('attachmentShowImages', '1'),
    ('attachmentEnable', '1'),
    ('attachmentEncryptFilenames', '1'),
    ('attachmentThumbnails', '1'),
    ('attachmentThumbWidth', '150'),
    ('attachmentThumbHeight', '150'),
    ('censorIgnoreCase', '1'),
    ('mostOnline', '1'),
    ('mostOnlineToday', '1'),
    ('mostDate', UNIX_TIMESTAMP()),
    ('allow_disableAnnounce', '1'),
    ('trackStats', '1'),
    ('userLanguage', '1'),
    ('titlesEnable', '1'),
    ('topicSummaryPosts', '15'),
    ('enableErrorLogging', '1'),
    ('max_image_width', '0'),
    ('max_image_height', '0'),
    ('onlineEnable', '0'),
    ('cal_holidaycolor', '000080'),
    ('cal_bdaycolor', '920AC4'),
    ('cal_eventcolor', '078907'),
    ('cal_enabled', '0'),
    ('cal_maxyear', '2010'),
    ('cal_minyear', '2004'),
    ('cal_daysaslink', '0'),
    ('cal_defaultboard', ''),
    ('cal_showeventsonindex', '0'),
    ('cal_showbdaysonindex', '0'),
    ('cal_showholidaysonindex', '0'),
    ('cal_showeventsoncalendar', '1'),
    ('cal_showbdaysoncalendar', '1'),
    ('cal_showholidaysoncalendar', '1'),
    ('cal_showweeknum', '0'),
    ('cal_maxspan', '7'),
    ('smtp_host', ''),
    ('smtp_port', '25'),
    ('smtp_username', ''),
    ('smtp_password', ''),
    ('mail_type', '0'),
    ('timeLoadPageEnable', '0'),
    ('totalTopics', '1'),
    ('totalMessages', '1'),
    ('simpleSearch', '0'),
    ('censor_vulgar', ''),
    ('censor_proper', ''),
    ('enablePostHTML', '0'),
    ('theme_allow', '1'),
    ('theme_default', '1'),
    ('theme_guests', '1'),
    ('enableEmbeddedFlash', '0'),
    ('xmlnews_enable', '1'),
    ('xmlnews_maxlen', '255'),
    ('hotTopicPosts', '15'),
    ('hotTopicVeryPosts', '25'),
    ('registration_method', '0'),
    ('send_validation_onChange', '0'),
    ('send_welcomeEmail', '1'),
    ('allow_editDisplayName', '1'),
    ('allow_hideOnline', '1'),
    ('allow_hideEmail', '1'),
    ('guest_hideContacts', '0'),
    ('spamWaitTime', '5'),
    ('pm_spam_settings', '10,5,20'),
    ('reserveWord', '0'),
    ('reserveCase', '1'),
    ('reserveUser', '1'),
    ('reserveName', '1'),
    ('reserveNames', '{$default_reserved_names}'),
    ('autoLinkUrls', '1'),
    ('banLastUpdated', '0'),
    ('smileys_dir', '{$boarddir}/Smileys'),
    ('smileys_url', '{$boardurl}/Smileys'),
    ('avatar_directory', '{$boarddir}/avatars'),
    ('avatar_url', '{$boardurl}/avatars'),
    ('avatar_max_height_external', '65'),
    ('avatar_max_width_external', '65'),
    ('avatar_action_too_large', 'option_html_resize'),
    ('avatar_max_height_upload', '65'),
    ('avatar_max_width_upload', '65'),
    ('avatar_resize_upload', '1'),
    ('avatar_download_png', '1'),
    ('failed_login_threshold', '3'),
    ('oldTopicDays', '120'),
    ('edit_wait_time', '90'),
    ('edit_disable_time', '0'),
    ('autoFixDatabase', '1'),
    ('allow_guestAccess', '1'),
    ('time_format', '{$default_time_format}'),
    ('number_format', '1234.00'),
    ('enableBBC', '1'),
    ('max_messageLength', '20000'),
    ('max_signatureLength', '300'),
    ('autoOptDatabase', '7'),
    ('autoOptMaxOnline', '0'),
    ('autoOptLastOpt', '0'),
    ('defaultMaxMessages', '15'),
    ('defaultMaxTopics', '20'),
    ('defaultMaxMembers', '30'),
    ('enableParticipation', '1'),
    ('recycle_enable', '0'),
    ('recycle_board', '0'),
    ('maxMsgID', '1'),
    ('enableAllMessages', '0'),
    ('fixLongWords', '0'),
    ('knownThemes', '1,2,3'),
    ('who_enabled', '1'),
    ('time_offset', '0'),
    ('cookieTime', '60'),
    ('lastActive', '15'),
    ('smiley_sets_known', 'default,classic'),
    ('smiley_sets_names', '{$default_smileyset_name}\n{$default_classic_smileyset_name}'),
    ('smiley_sets_default', 'default'),
    ('cal_days_for_index', '7'),
    ('requireAgreement', '1'),
    ('unapprovedMembers', '0'),
    ('default_personalText', ''),
    ('package_make_backups', '1'),
    ('databaseSession_enable', '{$databaseSession_enable}'),
    ('databaseSession_loose', '1'),
    ('databaseSession_lifetime', '2880'),
    ('search_cache_size', '50'),
    ('search_results_per_page', '30'),
    ('search_weight_frequency', '30'),
    ('search_weight_age', '25'),
    ('search_weight_length', '20'),
    ('search_weight_subject', '15'),
    ('search_weight_first_message', '10'),
    ('search_max_results', '1200'),
    ('permission_enable_deny', '0'),
    ('permission_enable_postgroups', '0'),
    ('permission_enable_by_board', '0');
# --------------------------------------------------------


#
# Table structure for table `smileys`
#

CREATE TABLE {$db_prefix} smileys (
    ID_SMILEY SMALLINT(
    5
) UNSIGNED NOT NULL AUTO_INCREMENT,
    CODE VARCHAR (
    30
) NOT NULL DEFAULT '',
    filename VARCHAR (
    48
) NOT NULL DEFAULT '',
    DESCRIPTION VARCHAR (
    80
) NOT NULL DEFAULT '',
    smileyRow TINYINT (
    4
) UNSIGNED NOT NULL DEFAULT '0',
    smileyOrder SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    hidden TINYINT (
    4
) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_SMILEY
)
    ) ENGINE = ISAM;

#
# Dumping data for table `smileys`
#

INSERT INTO {$db_prefix} smileys
    (CODE, filename, DESCRIPTION, smileyOrder, hidden)
VALUES (':)', 'smiley.gif', '{$default_smiley_smiley}', 0, 0),
    (';)', 'wink.gif', '{$default_wink_smiley}', 1, 0),
    (':D', 'cheesy.gif', '{$default_cheesy_smiley}', 2, 0),
    (';D', 'grin.gif', '{$default_grin_smiley}', 3, 0),
    ('>:(', 'angry.gif', '{$default_angry_smiley}', 4, 0),
    (':(', 'sad.gif', '{$default_sad_smiley}', 5, 0),
    (':o', 'shocked.gif', '{$default_shocked_smiley}', 6, 0),
    ('8)', 'cool.gif', '{$default_cool_smiley}', 7, 0),
    ('???', 'huh.gif', '{$default_huh_smiley}', 8, 0),
    ('::)', 'rolleyes.gif', '{$default_roll_eyes_smiley}', 9, 0),
    (':P', 'tongue.gif', '{$default_tongue_smiley}', 10, 0),
    (':-[', 'embarrassed.gif', '{$default_embarrassed_smiley}', 11, 0),
    (':-X', 'lipsrsealed.gif', '{$default_lips_sealed_smiley}', 12, 0),
    (':-\\', 'undecided.gif', '{$default_undecided_smiley}', 13, 0),
    (':-*', 'kiss.gif', '{$default_kiss_smiley}', 14, 0),
    (':\'(', 'cry.gif', '{$default_cry_smiley}', 15, 0),
    ('>:D', 'evil.gif', '{$default_evil_smiley}', 16, 1),
    ('^-^', 'azn.gif', '{$default_azn_smiley}', 17, 1),
    ('O0', 'afro.gif', '{$default_afro_smiley}', 18, 1);
# --------------------------------------------------------

#
# Table structure for table `themes`
#

CREATE TABLE {$db_prefix} themes (
    ID_MEMBER MEDIUMINT(
    8
) NOT NULL DEFAULT '0',
    ID_THEME TINYINT (
    4
) UNSIGNED NOT NULL DEFAULT '1',
    variable TINYTEXT NOT NULL,
    VALUE TEXT NOT NULL,
    PRIMARY KEY (
    ID_THEME,
    ID_MEMBER,
    variable(
    30
)),
    KEY ID_MEMBER (
    ID_MEMBER
)
    ) ENGINE = ISAM;

#
# Dumping data for table `themes`
#

INSERT INTO {$db_prefix} themes (ID_THEME, variable, VALUE)
VALUES (1, 'name', '{$default_theme_name}'),
    (1, 'theme_url', '{$boardurl}/Themes/default'),
    (1, 'images_url', '{$boardurl}/Themes/default/images'),
    (1, 'theme_dir', '{$boarddir}/Themes/default'),
    (1, 'show_bbc', '1'),
    (1, 'show_latest_member', '1'),
    (1, 'show_modify', '1'),
    (1, 'show_user_images', '1'),
    (1, 'show_blurb', '1'),
    (1, 'show_gender', '0'),
    (1, 'show_newsfader', '0'),
    (1, 'number_recent_posts', '0'),
    (1, 'show_member_bar', '1'),
    (1, 'linktree_link', '1'),
    (1, 'show_profile_buttons', '1'),
    (1, 'show_mark_read', '1'),
    (1, 'show_sp1_info', '1'),
    (1, 'linktree_inline', '0'),
    (1, 'show_board_desc', '1'),
    (1, 'newsfader_time', '5000'),
    (1, 'allow_no_censored', '0'),
    (1, 'additional_options_collapsable', '1'),
    (1, 'use_image_buttons', '1'),
    (1, 'enable_news', '1');
# --------------------------------------------------------

#
# Table structure for table `topics`
#

CREATE TABLE {$db_prefix} topics (
    ID_TOPIC MEDIUMINT(
    8
) UNSIGNED NOT NULL AUTO_INCREMENT,
    isSticky TINYINT (
    4
) NOT NULL DEFAULT '0',
    ID_BOARD SMALLINT (
    5
) UNSIGNED NOT NULL DEFAULT '0',
    ID_FIRST_MSG INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    ID_LAST_MSG INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MEMBER_STARTED MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ID_MEMBER_UPDATED MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    ID_POLL MEDIUMINT (
    8
) UNSIGNED NOT NULL DEFAULT '0',
    numReplies INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    numViews INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    LOCKED TINYINT (
    4
) NOT NULL DEFAULT '0',
    PRIMARY KEY (
    ID_TOPIC
),
    UNIQUE lastMessage (
    ID_LAST_MSG,
    ID_BOARD
),
    UNIQUE firstMessage (
    ID_FIRST_MSG,
    ID_BOARD
),
    UNIQUE poll (
    ID_POLL,
    ID_TOPIC
),
    KEY isSticky (
    isSticky
),
    KEY ID_BOARD (
    ID_BOARD
)
    ) ENGINE = ISAM;

#
# Dumping data for table `topics`
#

INSERT INTO {$db_prefix} topics
    (ID_TOPIC, ID_BOARD, ID_FIRST_MSG, ID_LAST_MSG, ID_MEMBER_STARTED, ID_MEMBER_UPDATED)
VALUES (1, 1, 1, 1, 0, 0);
# --------------------------------------------------------

CREATE TABLE {$db_prefix} ob_googlebot_stats (
    `agent` VARCHAR(
    40
) NOT NULL DEFAULT '',
    `board` VARCHAR (
    20
) NOT NULL DEFAULT '',
    `topic` VARCHAR (
    20
) NOT NULL DEFAULT '',
    `url` VARCHAR (
    100
) NOT NULL DEFAULT '',
    `lastvisit` INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    `frequency` INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    `visits` INT (
    10
) UNSIGNED NOT NULL DEFAULT '0',
    `timestamp` TIMESTAMP (
    14
) NOT NULL,
    KEY `agent` (
    `agent`
),
    KEY `board` (
    `board`
),
    KEY `topic` (
    `topic`
),
    KEY `url` (
    `url`
)
    ) ENGINE = ISAM;

INSERT IGNORE INTO `{$db_prefix}settings` (`variable`, `value`)
VALUES ('ob_googlebot_count_all_instances', '1');
INSERT IGNORE INTO `{$db_prefix}settings` (`variable`, `value`)
VALUES ('ob_googlebot_display_all_instances', '0');
INSERT IGNORE INTO `{$db_prefix}settings` (`variable`, `value`)
VALUES ('ob_googlebot_display_agent', '0');
INSERT IGNORE INTO `{$db_prefix}settings` (`variable`, `value`)
VALUES ('ob_googlebot_display_own_list', '0');
INSERT IGNORE INTO `{$db_prefix}settings` (`variable`, `value`)
VALUES ('ob_googlebot_count_most_online', '0');
INSERT IGNORE INTO `{$db_prefix}settings` (`variable`, `value`)
VALUES ('ob_googlebot_redirect_phpsessid', '1');
INSERT IGNORE INTO `{$db_prefix}settings` (`variable`, `value`)
VALUES ('ob_googlebot_stats', '1');


