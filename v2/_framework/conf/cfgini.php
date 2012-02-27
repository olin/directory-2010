;<?php die(); ?>
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;; OlinDirectory Config File          ;;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
; Configuration for OlinDirectory
; Settings should not be modified
;   by persons other than the site admin


;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;; Baseline Config (all servers)      ;;
;;  - Do not rename this section      ;;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

[main]

;Database config
db.adapter			= "mysql"
db.host				= "localhost"
db.dbname			= "directory"
db.username			= "directory"
db.password			= "xar$Q&y13"

;Email<Sendmail> Settings
email.backend		= "smtp"
email.host			= "smtp.olin.edu"

;Site-Admin settings
site.admin.uid		= "jstanton"
site.admin.name		= "Jeffrey Stanton"
site.admin.email	= "jeffrey.stanton@alumni.olin.edu"

;Security settings
site.ssl			= "disabled" ;"disabled" or "required"

;Mobile access settings
site.mobile.allow	= "jstanton" ;"user1,user2,..." OR "*" - comma-separated list of who can access mobile features

;Key3PO settings
key3po.baseurl		= "https://acl.olin.edu/commonauth" ;default, override per-host (see below)


;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;; ACL Config (production server)     ;;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

[acl]

db.username			= "directory"
db.password			= "xar$Q&y13"
site.ssl			= "required"
key3po.baseurl		= "https://acl.olin.edu/commonauth"
