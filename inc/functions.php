<?php
/*
 * OpenSimulator Functions
 * Version: 0.0.1
 * Author: Philippe Lemaire (djphil)
 * License: CC-BY-NC-SA 2.0 BE
 * Guarantee: None, any, no, zero
 */

/* GENERAL */
function debug($variable)
{
	echo '<pre>' . print_r($variable, true) . '</pre>';
}

function page_load_time()
{
    static $start;
    if (is_null($start))
    {
        $start = microtime(true);
    }
    else
    {
        $diff = round((microtime(true) - $start), 4);
        $start = null;
        return $diff;
    }
}

function generate_uuid()
{
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
    mt_rand(0, 0x0fff) | 0x4000,
    mt_rand(0, 0x3fff) | 0x8000,
    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
}

function generate_password($length = 10)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    return substr(str_shuffle($chars), 0, $length);
}

function username($db, $uuid)
{
    try {
        $sql = $db->prepare("
            SELECT FirstName, LastName
            FROM useraccounts
            WHERE PrincipalID = ?
        ");

        $sql->bindValue(1, $uuid, PDO::PARAM_STR);
        $sql->execute();

        if ($sql->rowCount() > 0)
        {
            while ($row = $sql->fetch(PDO::FETCH_ASSOC))
            {
                return $row['FirstName'].' '.$row['LastName'];
            }
        }
        return FALSE;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

function get_userdatas_by_email($db, $email)
{
    try {
        $sql = $db->prepare("
            SELECT PrincipalID, FirstName, LastName
            FROM useraccounts
            WHERE Email = ?
        ");

        $sql->bindValue(1, $email, PDO::PARAM_STR);
        $sql->execute();

        if ($sql->rowCount() > 0)
        {
            while ($row = $sql->fetch(PDO::FETCH_ASSOC))
            {
                return $row;
            }
        }
        return FALSE;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

function username_exist($db, $firstname, $lastname)
{
    try {
        $sql = $db->prepare("
            SELECT FirstName, LastName
            FROM useraccounts
            WHERE FirstName = ?
            AND LastName = ?
        ");
        $sql->bindValue(1, $firstname, PDO::PARAM_STR);
        $sql->bindValue(2, $lastname, PDO::PARAM_STR);
        $sql->execute();
        $count = $sql->rowCount();
        $sql->closeCursor();
        $db = null;
        if ($count > 0) return TRUE;
        return FALSE;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

function email_exist($db, $email)
{
    try {
        $sql = $db->prepare("
            SELECT Email
            FROM useraccounts
            WHERE Email = ?
        ");
        $sql->bindValue(1, $email, PDO::PARAM_STR);
        $sql->execute();
        $count = $sql->rowCount();
        $sql->closeCursor();
        $db = null;
        if ($count > 0) return TRUE;
        return FALSE;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

/* REGISTRATION */
// generate_uuid() exec('uuidgen') system("uuidgen");
function verify_password($db, $uuid, $password)
{
    $passwordSalt = md5(str_replace("-", "", shell_exec('uuidgen')));
    $passwordHash = md5(md5($password).":".$passwordSalt);

    try {
        $sql = $db->prepare("
            SELECT UUID
            FROM auth
            WHERE UUID = ?
            AND passwordHash = ?
            AND passwordSalt = ?
        ");
        $sql->bindValue(1, $uuid, PDO::PARAM_STR);
        $sql->bindValue(2, $passwordHash, PDO::PARAM_STR);
        $sql->bindValue(3, $passwordSalt, PDO::PARAM_STR);
        $sql->execute();
        $result = $sql->rowCount();
        $sql->closeCursor();
        $db = null;
        return $result;
    }
    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

function change_password($db, $uuid, $password)
{
    $passwordSalt = md5(str_replace("-", "", shell_exec('uuidgen')));
    $passwordHash = md5(md5($password).":".$passwordSalt);

    try {
        $sql = $db->prepare("
            UPDATE auth
            SET passwordHash = ?,
                passwordSalt = ?
            WHERE UUID = ?
        ");
        $sql->bindValue(1, $passwordHash, PDO::PARAM_STR);
        $sql->bindValue(2, $passwordSalt, PDO::PARAM_STR);
        $sql->bindValue(3, $uuid, PDO::PARAM_STR);
        $sql->execute();
        $result = $sql->rowCount();
        $sql->closeCursor();
        $db = null;
        return $result;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

function create_useraccount($db, $uuid, $firstname, $lastname, $email)
{
    try {
        $sql = $db->prepare("
            INSERT INTO useraccounts (
                PrincipalID, 
                ScopeID, 
                FirstName, 
                LastName, 
                Email, 
                ServiceURLs, 
                Created, 
                UserLevel, 
                UserFlags, 
                UserTitle,
                active
            )
            VALUE (
                :PrincipalID, 
                '00000000-0000-0000-0000-000000000000',
                :FirstName, 
                :LastName, 
                :Email, 
                'HomeURI=\t\nInventoryServerURI=\t\nAssetServerURI=\t',
                ".time().", 								
                '0',
                '0',
                'Resident',
                '0'
            )
        ");

        $sql->bindValue(':PrincipalID', $uuid, PDO::PARAM_STR);
        $sql->bindValue(':FirstName', $firstname, PDO::PARAM_STR);
        $sql->bindValue(':LastName', $lastname, PDO::PARAM_STR);
        $sql->bindValue(':Email', $email, PDO::PARAM_STR);
        $sql->execute();
        $sql->closeCursor();
        $db = NULL;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

function create_userauth($db, $uuid, $password)
{
    $passwordSalt = md5(str_replace("-", "", shell_exec('uuidgen')));
    $passwordHash = md5(md5($password).":".$passwordSalt);

    try {
        $sql = $db->prepare("
            INSERT INTO auth (
                UUID, 
                passwordHash, 
                passwordSalt, 
                webLoginKey, 
                accountType
            )
            VALUES (
                :UUID, 
                :passwordHash,
                :passwordSalt, 
                '00000000-0000-0000-0000-000000000000', 
                'UserAccount' 
            )
        ");

        $sql->bindValue(':UUID', $uuid, PDO::PARAM_STR);
        $sql->bindValue(':passwordHash', $passwordHash, PDO::PARAM_STR);
        $sql->bindValue(':passwordSalt', $passwordSalt, PDO::PARAM_STR);
        $sql->execute();
        $sql->closeCursor();
        $db = NULL;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

function create_griduser($db, $uuid)
{
    try {
        $sql = $db->prepare("
            INSERT INTO griduser (
                UserID, 
                HomeRegionID, 
                HomePosition, 
                HomeLookAt, 
                LastRegionID, 
                LastPosition, 
                LastLookAt, 
                Online, 
                Login, 
                Logout
            )
            VALUES (
                :UserID, 
                '00000000-0000-0000-0000-000000000000', 
                '<128,128,128>', 
                '<100,100,100>', 
                '00000000-0000-0000-0000-000000000000', 
                '<128,128,128>', 
                '<100,100,100>', 
                'false', 
                ".time().",
                ".time()."
            )
        ");

        $sql->bindValue(':UserID', $uuid, PDO::PARAM_STR);
        $sql->execute();
        $sql->closeCursor();
        $db = NULL;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

function create_inventory($db, $uuid)
{
    $master = generate_uuid();
    $scope = "00000000-0000-0000-0000-000000000000";

    $inventory = array (
        // 'User Defined' => -1,
        'Textures' =>  0,
        'Sounds' =>  1,
        'Calling Cards' =>  2,
        'Friends' =>  2,
        'All' =>  2,
        'Landmarks' =>  3,
        'Clothing' =>  5,
        'Objects' =>  6,
        'Notecards' =>  7,
        'My Inventory' =>  8,
        'Scripts' => 10,
        'Body Parts' => 13,
        'Trash' => 14,
        'Photo Album' => 15,
        'Lost And Found' => 16,
        'Animations' => 20,
        'Gestures' => 21,
        'Favorites' => 23,
        'Current Outfit' => 46,
        'My Outfits' => 48,
        // 'Meshes' => 49,
        'Recieved Items' => 50
        // 'My Suitcase' => 100
    );

    foreach($inventory as $invfoldername => $invtype)
    {
        $invfolderuuid = generate_uuid();

        // if ($invtype == 9) // v0.8
        if ($invtype === 8) // v0.9
        {
            $invfolderuuid = $master;
            $parent = $scope;
        }

        else
        {
            $parent = $master;
        }

        try {
            $sql = $db->prepare("
                INSERT INTO inventoryfolders (
                    folderName, 
                    type, 
                    version, 
                    folderID, 
                    agentID,
                    parentFolderID
                )
                VALUES (
                    :folderName, 
                    :type,
                    :version, 
                    :folderID, 
                    :agentID,
                    :parentFolderID
                )
            ");
            $sql->bindValue(':folderName', $invfoldername, PDO::PARAM_STR);
            $sql->bindValue(':type', $invtype, PDO::PARAM_INT);
            $sql->bindValue(':version', '1', PDO::PARAM_INT);
            $sql->bindValue(':folderID', $invfolderuuid, PDO::PARAM_STR);
            $sql->bindValue(':agentID', $uuid, PDO::PARAM_STR);
            $sql->bindValue(':parentFolderID', $parent, PDO::PARAM_STR);
            $sql->execute();
        }

        catch(PDOException $e) {
            $message = '
                <pre>
                    Unable to query database ...
                    Error code: '.$e->getCode().'
                    Error file: '.$e->getFile().'
                    Error line: '.$e->getLine().'
                    Error data: '.$e->getMessage().'
                </pre>
            ';
            die($message);
        }
    }
    $sql->closeCursor();
    $db = NULL;
}

function get_folder_uuid($db, $agentID, $folderName)
{
    try {
        $sql = $db->prepare("
            SELECT folderID
            FROM inventoryfolders
            WHERE agentID = ?
            AND folderName = ?
        ");
        $sql->bindValue(1, $agentID, PDO::PARAM_STR);
        $sql->bindValue(2, $folderName, PDO::PARAM_STR);
        $sql->execute();

        $folder_uuid = "";
        if ($sql->rowCount() > 0)
        {
            while ($row = $sql->fetch(PDO::FETCH_ASSOC))
            {
                $folder_uuid = $row['folderID'];
            }
        }
        $sql->closeCursor();
        $db = NULL;
        return $folder_uuid;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

function get_folder_items($db, $avatarID, $folder_uuid)
{
    try {
        $sql = $db->prepare("
            SELECT *
            FROM inventoryitems
            WHERE avatarID = ?
            AND parentFolderID = ?
        ");
        $sql->bindValue(1, $avatarID, PDO::PARAM_STR);
        $sql->bindValue(2, $folder_uuid, PDO::PARAM_STR);
        $sql->execute();

        $items = array();
        
        if ($sql->rowCount() > 0)
        {
            while ($row = $sql->fetch(PDO::FETCH_ASSOC))
            {
                $items[] = $row;
            }
        }
        $sql->closeCursor();
        $db = NULL;
        return $items;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

function create_folder($db, $folderName, $agentID, $parentFolder)
{
    $folderID = generate_uuid();

    try {
        $sql = $db->prepare("
            INSERT INTO inventoryfolders (
                folderName,
                type,                
                version, 
                folderID,
                agentID,
                parentFolderID
            ) 
            VALUES (
                :folderName, 
                :type,
                :version,
                :folderID,
                :agentID,
                :parentFolder
            )
        ");

        $sql->bindValue(':folderName', $folderName, PDO::PARAM_STR);
        $sql->bindValue(':type', -1, PDO::PARAM_INT);
        $sql->bindValue(':version', 1, PDO::PARAM_INT);
        $sql->bindValue(':folderID', $folderID, PDO::PARAM_STR);
        $sql->bindValue(':agentID', $agentID, PDO::PARAM_STR);
        $sql->bindValue(':parentFolder', $parentFolder, PDO::PARAM_STR);
        $sql->execute();
        $sql->closeCursor();
        $db = NULL;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

function copy_items($db, $newAccountID, $items, $parentFolderID)
{
    try {
        $sql = $db->prepare("
            INSERT INTO inventoryitems (
                assetID, 
                assetType, 
                inventoryName,
                inventoryDescription,
                inventoryNextPermissions,
                inventoryCurrentPermissions,
                invType,
                creatorID,
                inventoryBasePermissions,
                inventoryEveryOnePermissions,
                salePrice,
                saleType,
                creationDate,
                groupID,
                groupOwned,
                flags,
                inventoryID,
                avatarID,
                parentFolderID,
                inventoryGroupPermissions
            ) 
            VALUES (
                :assetID, 
                :assetType, 
                :inventoryName,
                :inventoryDescription,
                :inventoryNextPermissions,
                :inventoryCurrentPermissions,
                :invType,
                :creatorID,
                :inventoryBasePermissions,
                :inventoryEveryOnePermissions,
                :salePrice,
                :saleType,
                :creationDate,
                :groupID,
                :groupOwned,
                :flags,
                :inventoryID,
                :avatarID,
                :parentFolderID,
                :inventoryGroupPermissions
            )
        ");

        foreach($items AS $arrays => $keys)
        {
            $inventoryID = generate_uuid();

            $sql->bindValue('assetID', $keys['assetID'], PDO::PARAM_STR);
            $sql->bindValue('assetType', $keys['assetType'], PDO::PARAM_INT);
            $sql->bindValue('inventoryName', $keys['inventoryName'], PDO::PARAM_STR);
            $sql->bindValue('inventoryDescription', $keys['inventoryDescription'], PDO::PARAM_STR);
            $sql->bindValue('inventoryNextPermissions', $keys['inventoryNextPermissions'], PDO::PARAM_INT);
            $sql->bindValue('inventoryCurrentPermissions', $keys['inventoryCurrentPermissions'], PDO::PARAM_INT);
            $sql->bindValue('invType', $keys['invType'], PDO::PARAM_INT);
            $sql->bindValue('creatorID', $keys['creatorID'], PDO::PARAM_STR);
            $sql->bindValue('inventoryBasePermissions', $keys['inventoryBasePermissions'], PDO::PARAM_INT);
            $sql->bindValue('inventoryEveryOnePermissions', $keys['inventoryEveryOnePermissions'], PDO::PARAM_INT);
            $sql->bindValue('salePrice', $keys['salePrice'], PDO::PARAM_INT);
            $sql->bindValue('saleType', $keys['saleType'], PDO::PARAM_INT);
            $sql->bindValue('creationDate', $keys['creationDate'], PDO::PARAM_INT);
            $sql->bindValue('groupID', $keys['groupID'], PDO::PARAM_STR);
            $sql->bindValue('groupOwned', $keys['groupOwned'], PDO::PARAM_INT);
            $sql->bindValue('flags', $keys['flags'], PDO::PARAM_INT);
            $sql->bindValue('inventoryID', $inventoryID, PDO::PARAM_STR);
            $sql->bindValue('avatarID', $newAccountID, PDO::PARAM_STR);
            $sql->bindValue('parentFolderID', $parentFolderID, PDO::PARAM_STR);
            $sql->bindValue('inventoryGroupPermissions', $keys['inventoryGroupPermissions'], PDO::PARAM_INT);
            $sql->execute();
        }
        $sql->closeCursor();
        $db = NULL;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

/* APPEARANCE */
function get_appearance($db, $PrincipalID)
{
    try {
        $sql = $db->prepare("
            SELECT Name, Value
            FROM avatars
            WHERE PrincipalID = ?
        ");
        $sql->bindValue(1, $PrincipalID, PDO::PARAM_STR);
        $sql->execute();
        
        $appearance = array();

        if ($sql->rowCount() > 0)
        {        
            while ($row = $sql->fetch(PDO::FETCH_ASSOC))
            {
                $appearance[] = $row;
                // array_push($appearance, $row['Name'], $row['Value']);
            }
        }
        $sql->closeCursor();
        $db = NULL;
        return $appearance;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

function delete_appearance($db, $PrincipalID)
{
    try {
        $sql = $db->prepare("
            DELETE FROM avatars
            WHERE PrincipalID = :PrincipalID
        ");
        $sql->bindValue(':PrincipalID', $PrincipalID, PDO::PARAM_STR);
        $sql->execute();
        $sql->closeCursor();
        $db = NULL;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

function copy_appearance($db, $PrincipalID, $Name, $Value)
{
    try {
        $sql = $db->prepare("
            INSERT INTO avatars (
                PrincipalID, 
                Name, 
                Value
            ) 
            VALUES (
                :PrincipalID, 
                :Name,
                :Value
            )
        ");

        $sql->bindValue(':PrincipalID', $PrincipalID, PDO::PARAM_STR);
        $sql->bindValue(':Name', $Name, PDO::PARAM_STR);
        $sql->bindValue(':Value', $Value, PDO::PARAM_STR);
        $sql->execute();
        $sql->closeCursor();
        $db = NULL;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

function search_inventoryID($db, $assetID, $avatarID, $parentFolderID)
{
    try {
        $sql = $db->prepare("
            SELECT inventoryID
            FROM inventoryitems
            WHERE assetID = ?
            AND avatarID = ?
            AND parentFolderID = ?
        ");
        $sql->bindValue(1, $assetID, PDO::PARAM_STR);
        $sql->bindValue(2, $avatarID, PDO::PARAM_STR);
        $sql->bindValue(3, $parentFolderID, PDO::PARAM_STR);
        $sql->execute();

        $inventoryID = "";
        
        if ($sql->rowCount() > 0)
        {
            while ($row = $sql->fetch(PDO::FETCH_ASSOC))
            {
                $inventoryID = $row['inventoryID'];
            }
        }

        $sql->closeCursor();
        $db = NULL;
        return $inventoryID;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

function get_assetID_by_inventoryID($db, $avatarID, $inventoryID)
{
    try {
        $sql = $db->prepare("
            SELECT assetID
            FROM inventoryitems
            WHERE avatarID = ? 
            AND inventoryID = ?
        ");
        $sql->bindValue(1, $avatarID, PDO::PARAM_STR);
        $sql->bindValue(2, $inventoryID, PDO::PARAM_STR);
        $sql->execute();

        $assetID = "";
        if ($sql->rowCount() > 0)
        {
            while ($row = $sql->fetch(PDO::FETCH_ASSOC))
            {
                $assetID = $row['assetID'];
            }
        }
        $sql->closeCursor();
        $db = NULL;
        return $assetID;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

function get_inventoryID_by_assetID($db, $avatarID, $assetID)
{
    try {
        $sql = $db->prepare("
            SELECT inventoryID 
            FROM inventoryitems
            WHERE avatarID = ? 
            AND assetID = ?
        ");
        $sql->bindValue(1, $avatarID, PDO::PARAM_STR);
        $sql->bindValue(2, $assetID, PDO::PARAM_STR);
        $sql->execute();

        $inventoryID = "";
        if ($sql->rowCount() > 0)
        {
            while ($row = $sql->fetch(PDO::FETCH_ASSOC))
            {
                $inventoryID = $row['inventoryID'];
            }
        }
        $sql->closeCursor();
        $db = NULL;
        return $inventoryID;
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }
}

function create_appearance($db, $to_uuid, $from_uuid)
{
    /*copy folder*/
    $model_name = username($db, $from_uuid);
    $my_inventory_uuid = get_folder_uuid($db, $to_uuid, "My Inventory");
    create_folder($db, $model_name, $to_uuid, $my_inventory_uuid);

    /*copy items*/
    $folder_uuid = get_folder_uuid($db, $from_uuid, $model_name);
    $items = get_folder_items($db, $from_uuid, $folder_uuid);
    $parentFolderID = get_folder_uuid($db, $to_uuid, $model_name);
    copy_items($db, $to_uuid, $items, $parentFolderID);

    /*copy appearance*/
    $appearance = get_appearance($db, $from_uuid);
    // delete_appearance($db, $newAccountID);
    delete_appearance($db, $to_uuid);

    foreach($appearance AS $key => $val)
    {
        $Name = $val['Name'];
        $Value = $val['Value'];

        // If no wearable or _ap_
        if (strpos($val['Name'], 'Wearable') === false && strpos($val['Name'], '_ap_') === false) {;}

        // If wearable
        if (strpos($val['Name'], 'Wearable') !== false)
        {
            $buffer = explode(":", $val['Value']);
            $inventoryID = $buffer[0]; // uuid 1 = inventoryID from inventoryitems
            $assetID = $buffer[1]; // uuid 2 = assetID from inventoryitems

            $parentFolderID = get_folder_uuid($db, $to_uuid, $model_name);
            $new_inventoryID = search_inventoryID($db, $assetID, $to_uuid, $parentFolderID);
            $Value = $new_inventoryID.":".$assetID;
        }

        // If _ap_ (attachment)
        if (strpos($val['Name'], '_ap_') !== false)
        {
            $inventoryID = $val['Value'];
            $assetID = get_assetID_by_inventoryID($db, $from_uuid, $inventoryID);
            $new_inventoryID = get_inventoryID_by_assetID($db, $to_uuid, $assetID);
            $Value = $new_inventoryID;
            $parentFolderID = get_folder_uuid($db, $to_uuid, $model_name);
            $new_inventoryID = search_inventoryID($db, $assetID, $to_uuid, $parentFolderID);
            $Value = $new_inventoryID;
        }

        // Copy Appearance
        copy_appearance($db, $to_uuid, $Name, $Value);
    } 
}

/* EMAIL */
function sanitize_email($email)
{
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        return $email;
    }

    else
    {
        return FALSE;
    }
}

function send_email_to_user($uuid, $firstname, $lastname, $password, $email, $title)
{
    $subject = $title.' - Your personal user account information';
    $message = "
    <html>
    <head>
        <title>".$title."</title>
        <style>body {font-family: Calibri, Candara, Segoe, 'Segoe UI', Optima, Arial, sans-serif;}</style>
    </head>
    <body>
        <h1>".$title."</h1>
        <h2>Your personal user account information:</h2>
        <p>
            <strong>Firstname:</strong> ".$firstname."</br />
            <strong>Lastname:</strong> ".$lastname."</br />
            <strong>Password:</strong> ".$password."</br />
            <strong>Email:</strong> ".$email."</br />
            <strong>Uuid:</strong> ".$uuid."</br />
        </p>
            <h3>Don't share, don't lose, don't forget ...</h3>
            <p>Thank you for using ".$title."</p>
            <p>".$title." Team</p>

    </body>
    </html>
    ";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html; charset=UTF-8"."\r\n";
    $headers .= 'From: <noreply@noreply.com>'."\r\n";
    $headers .= 'Cc: noreply@noreply.com'."\r\n";
    mail($email, $subject, $message, $headers);
}

function send_email_to_admin($uuid, $firstname, $lastname, $email, $title, $webmaster_email)
{
    $subject = $title.' - A new account has been created ...';
    $message = "
    <html>
    <head>
        <title>".$title."</title>
        <style>body {font-family: Calibri, Candara, Segoe, 'Segoe UI', Optima, Arial, sans-serif;}</style>
    </head>
    <body>
        <h1>".$title."</h1>
        <h2>A new account has been created ...</h2>
        <p>
            <strong>Firstname:</strong> ".$firstname."</br />
            <strong>Lastname:</strong> ".$lastname."</br />
            <strong>Email:</strong> ".$email."</br />
            <strong>Uuid:</strong> ".$uuid."</br />
            </br />".$title." Team
        </p>    
    </body>
    </html>
    ";
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html; charset=UTF-8"."\r\n";
    $headers .= 'From: <noreply@noreply.com>'."\r\n";
    $headers .= 'Cc: noreply@noreply.com'."\r\n";
    mail($webmaster_email, $subject, $message, $headers);
}

function change_email($db, $uuid, $email)
{
    $sql = $db->prepare("
        UPDATE useraccounts
        SET Email = ?
        WHERE PrincipalID = ?
    ");

    $sql->bindValue(1, $email, PDO::PARAM_STR);
    $sql->bindValue(2, $uuid, PDO::PARAM_STR);
    $sql->execute();

    $_SESSION['flash']['success'] = "Your new Email Adress is now <strong>".$email."</strong>";
    $_SESSION['email'] = $email;
    $sql->closeCursor();
    $db = NULL;
}
?>