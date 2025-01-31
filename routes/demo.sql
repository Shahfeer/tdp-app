BEGIN
    SELECT usr.user_id, usr.user_master_id, usr.parent_id,usr.logo_media, rm.rights_name, rm.rights_id
    FROM user_management usr
    LEFT JOIN user_rights ur ON usr.user_id = ur.user_id
    LEFT JOIN rights_master rm ON rm.rights_id = ur.rights_id
    WHERE ur.user_id = in_user_id;

    
END


