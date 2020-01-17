<?php

/*
 * This file is part of the Kynno/SmartBotsBundle package.
 *
 * (c) Kynno <contact@kynno.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kynno\SmartBotsBundle\Service;

use Kynno\SmartBotsBundle\Exception\SmartBotsException;
use Symfony\Component\HttpClient\HttpClient;

abstract class AbstractSmartBotsCommands
{
    /** @var string|null $APIUrl Where the POST request will be sent */
    protected $APIUrl;
    /** @var string|null $APIKey Your developer's API Key */
    protected $APIKey;
    /** @var string $botName SmartBot's name */
    protected $botName;
    /** @var string $botSecret The SmartBot's secret authentication code */
    protected $botSecret;
    /** @var array $queryParams The query variables, displayed as an array */
    protected $queryParams = [];

    /**
     * Create the query string so that it can be accepted in a URL.
     * Inject credentials to the queryParams and return an URL-encoded string.
     */
    private function buildQueryString(): string
    {
        $this->queryParams['apikey']  = $this->APIKey;
        $this->queryParams['botname'] = $this->botName;
        $this->queryParams['secret']  = $this->botSecret;

        return http_build_query($this->queryParams);
    }

    /**
     * Run the command requested.
     */
    private function runAction(): array
    {
        $queryResponseArray = [];
        $client             = HttpClient::create();
        $request            = $client->request('GET', $this->APIUrl . '?' . $this->buildQueryString());
        parse_str($request->getContent(), $queryResponseArray);
        $queryResponseArray['request_object'] = $request;

        if ('OK' !== $queryResponseArray['result']) {
            throw new SmartBotsException($queryResponseArray['resulttext']);
        }

        return $queryResponseArray;
    }

    /**
     * These are the official functions from SmartBots.
     * Read more at https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands
     * Note: The parameter "custom" is always optional.
     */

    /**
     * Activates a specific group (for example, to get build rights on the parcel).
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/activate_group
     *
     * @param string $groupuuid
     *                          the UUID of the group to activate
     *                          Special values:
     *                          LAND - set the group to the current parcel's group (see examples)
     *                          00000000-0000-0000-0000-000000000000 - remove active group
     *                          Obliviously, the bot has to be a member of the group already to activate it
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function activate_group(string $groupuuid = '', string $custom = ''): array
    {
        $this->queryParams              = [];
        $this->queryParams['action']    = 'activate_group';
        $this->queryParams['groupuuid'] = $groupuuid;
        $this->queryParams['custom']    = $custom;

        return $this->runAction();
    }

    /**
     * Returns a list of the bot's attachments (including HUD objects).
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/attachments
     *
     * @param string $skipnames
     *                           (optional) Skip attachment (do not return) if its name contains this substring (case
     *                           insensitive)
     * @param string $matchnames
     *                           (optional) Return only attachment those which name contain this substring (case
     *                           insensitive)
     * @param string $matchuuid
     *                           (optional) Return attachment with this UUID only
     *
     * @return array
     *               result          OK - command completed successfully
     *               FAIL - command failed
     *               resulttext      Detailed reason for the failure.
     *               custom          The value from input "custom" parameter. See above.
     *               total           The total number of attachments (regardless of any filters, see below).
     *               attachments     The list of all bot's attachments, object UUIDs and names.
     *               May be affected by skip* and match* parameters. See the "Return value" section below.
     */
    public function attachments(
        string $skipnames = '',
        string $matchnames = '',
        string $matchuuid = '',
        string $custom = ''
    ): array {
        $this->queryParams               = [];
        $this->queryParams['action']     = 'attachments';
        $this->queryParams['skipnames']  = $skipnames;
        $this->queryParams['matchnames'] = $matchnames;
        $this->queryParams['matchuuid']  = $matchuuid;
        $this->queryParams['custom']     = $custom;

        return $this->runAction();
    }

    /**
     * Returns a list of a resident's groups.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/avatar_groups
     *
     * @param string $avatar
     *                           Avatar UUID to fetch groups for
     * @param string $skipnames
     *                           (optional) Skip group (do not return) if its name contains this substring (case
     *                           insensitive)
     * @param string $matchnames
     *                           (optional) Return only groups those which names contain this substring (case
     *                           insensitive)
     * @param string $matchuuid
     *                           (optional) Return groups with this UUID only
     * @param string $custom
     *
     * @return array
     *               result          OK - command completed successfully
     *               FAIL - command failed
     *               resulttext      Detailed reason for the failure.
     *               custom          The value from input "custom" parameter. See above.
     *               total           The total number of groups (regardless of any filters, see below).
     *               groups          The list of resident's groups: UUIDs and names.
     *               This list may be affected by skip* and match* parameters. See the "Return value" section below.
     */
    public function avatar_groups($avatar = '', $skipnames = '', $matchnames = '', $matchuuid = '', $custom = ''): array
    {
        $this->queryParams               = [];
        $this->queryParams['action']     = 'avatar_groups';
        $this->queryParams['avatar']     = $avatar;
        $this->queryParams['skipnames']  = $skipnames;
        $this->queryParams['matchnames'] = $matchnames;
        $this->queryParams['matchuuid']  = $matchuuid;
        $this->queryParams['custom']     = $custom;

        return $this->runAction();
    }

    /**
     * Returns a list of a resident's picks.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/avatar_picks
     *
     * @param string $avatar
     *                           Avatar UUID to fetch picks for
     * @param string $skipnames
     *                           (optional) Skip pick (do not return) if its name contains this substring (case
     *                           insensitive)
     * @param string $matchnames
     *                           (optional) Return only picks which names contain this substring (case insensitive)
     * @param string $matchuuid
     *                           (optional) Return pick with this parcel UUID only
     *
     * @return array
     *               result          OK - command completed successfully
     *               FAIL - command failed
     *               resulttext      Detailed reason for the failure.
     *               custom          The value from input "custom" parameter. See above.
     *               total           The total number of picks (regardless of any filters, see below).
     *               picks           The list of resident's picks: parcel UUIDs and names.
     *               This list may be affected by skip* and match* parameters. See the "Return value" section below.
     */
    public function avatar_picks(
        string $avatar = '',
        string $skipnames = '',
        string $matchnames = '',
        string $matchuuid = '',
        string $custom = ''
    ): array {
        $this->queryParams               = [];
        $this->queryParams['action']     = 'avatar_picks';
        $this->queryParams['avatar']     = $avatar;
        $this->queryParams['skipnames']  = $skipnames;
        $this->queryParams['matchnames'] = $matchnames;
        $this->queryParams['matchuuid']  = $matchuuid;
        $this->queryParams['custom']     = $custom;

        return $this->runAction();
    }

    /**
     * Returns avatar L$ balance.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/get_balance
     *
     * @return array
     *               result          OK - command completed successfully
     *               FAIL - command failed
     *               resulttext      Detailed reason for the failure.
     *               balance         The balance of the bot
     */
    public function get_balance(string $custom = ''): array
    {
        $this->queryParams           = [];
        $this->queryParams['action'] = 'get_balance';
        $this->queryParams['custom'] = $custom;

        return $this->runAction();
    }

    /**
     * Commands bot to send an inventory item or folder to specific avatar.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/give_inventory
     *
     * @param string $avatar
     *                       The avatar UUID
     * @param string $object
     *                       The inventory or folder UUID of the item. Use the Personal Bot Control Panel or
     *                       listinventory API command to get this UUID.
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function give_inventory(string $avatar = '', string $object = '', string $custom = ''): array
    {
        $this->queryParams           = [];
        $this->queryParams['action'] = 'give_inventory';
        $this->queryParams['avatar'] = $avatar;
        $this->queryParams['object'] = $object;
        $this->queryParams['custom'] = $custom;

        return $this->runAction();
    }

    /**
     * Commands bot to send money (L$) to specific avatar.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/give_money
     *
     * @param string $avatar
     *                        The avatar UUID
     * @param string $amount
     *                        The amount of money to give
     * @param string $comment
     *                        (optional) Text comment for the money transaction
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function give_money(
        string $avatar = '',
        string $amount = '',
        string $comment = '',
        string $custom = ''
    ): array {
        $this->queryParams            = [];
        $this->queryParams['action']  = 'give_money';
        $this->queryParams['avatar']  = $avatar;
        $this->queryParams['amount']  = $amount;
        $this->queryParams['comment'] = $comment;
        $this->queryParams['custom']  = $custom;

        return $this->runAction();
    }

    /**
     * Commands bot to send money (L$) to a specific object. Also see give_money command.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/give_money
     *
     * @param string $object_uuid
     *                            The object UUID
     * @param string $amount
     *                            The amount of money to give
     * @param string $object_name
     *                            (optional) Object name
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function give_money_object(
        string $object_uuid = '',
        string $amount = '',
        string $object_name = '',
        string $custom = ''
    ): array {
        $this->queryParams                = [];
        $this->queryParams['action']      = 'give_money_object';
        $this->queryParams['object_uuid'] = $object_uuid;
        $this->queryParams['amount']      = $amount;
        $this->queryParams['object_name'] = $object_name;
        $this->queryParams['custom']      = $custom;

        return $this->runAction();
    }

    /**
     * Ejects resident from the group.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/group_eject
     *
     * @param string $avatar
     *                          the UUID of the resident
     * @param string $groupuuid
     *                          the UUID of the group
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function group_eject(string $avatar = '', string $groupuuid = '', string $custom = ''): array
    {
        $this->queryParams              = [];
        $this->queryParams['action']    = 'group_eject';
        $this->queryParams['avatar']    = $avatar;
        $this->queryParams['groupuuid'] = $groupuuid;
        $this->queryParams['custom']    = $custom;

        return $this->runAction();
    }

    /**
     * Sends a group invitation to a specific resident.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/group_invite
     *
     * @param string $avatar
     *                                 the UUID of the resident
     * @param string $groupuuid
     *                                 the UUID of the group
     * @param string $roleuuid
     *                                 the UUID of the group role (NULL_KEY for "Everyone")
     * @param string $check_membership
     *                                 set to 1 if you want to ignore existing group members (see "Comments" below)
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function group_invite(
        string $avatar = '',
        string $groupuuid = '',
        string $roleuuid = '',
        string $check_membership = '',
        string $custom = ''
    ): array {
        $this->queryParams                     = [];
        $this->queryParams['action']           = 'group_invite';
        $this->queryParams['avatar']           = $avatar;
        $this->queryParams['groupuuid']        = $groupuuid;
        $this->queryParams['roleuuid']         = $roleuuid;
        $this->queryParams['check_membership'] = $check_membership;
        $this->queryParams['custom']           = $custom;

        return $this->runAction();
    }

    /**
     * Tries to join a group by UUID.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/group_join
     *
     * @param string $groupuuid
     *                          the UUID of the group
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function group_join(string $groupuuid = '', string $custom = ''): array
    {
        $this->queryParams              = [];
        $this->queryParams['action']    = 'group_join';
        $this->queryParams['groupuuid'] = $groupuuid;
        $this->queryParams['custom']    = $custom;

        return $this->runAction();
    }

    /**
     * Commands bot to leave the group specified by a UUID.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/group_leave
     *
     * @param string $groupuuid
     *                          the UUID of the group
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function group_leave(string $groupuuid = '', string $custom = ''): array
    {
        $this->queryParams              = [];
        $this->queryParams['action']    = 'group_leave';
        $this->queryParams['groupuuid'] = $groupuuid;
        $this->queryParams['custom']    = $custom;

        return $this->runAction();
    }

    /**
     * Sends an Instant Message to a specific user.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/im
     *
     * @param string $slname
     *                        Second Life login name of avatar or avatar UUID
     * @param string $message
     *                        The message to send
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function im(string $slname = '', string $message = '', string $custom = ''): array
    {
        $this->queryParams            = [];
        $this->queryParams['action']  = 'im';
        $this->queryParams['slname']  = $slname;
        $this->queryParams['message'] = $message;
        $this->queryParams['custom']  = $custom;

        return $this->runAction();
    }

    /**
     * Returns avatar Second Life name by UUID. The command works in opposition to name2key.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/key2name
     *
     * @param string $key
     *                             the UUID of the avatar
     * @param int    $request_case
     *                             set to 1 if you want to get SL name in exact case (otherwise name may come in lower
     *                             case)
     *
     * @return array
     *               result          OK - command completed successfully
     *               FAIL - command failed
     *               resulttext      Detailed reason for the failure.
     *               custom          The value from input "custom" parameter. See above.
     *               key             The UUID you've sent
     *               name            Second Life name of the avatar
     */
    public function key2name(string $key = '', int $request_case = 1, string $custom = ''): array
    {
        $this->queryParams                 = [];
        $this->queryParams['action']       = 'key2name';
        $this->queryParams['key']          = $key;
        $this->queryParams['request_case'] = $request_case;
        $this->queryParams['custom']       = $custom;

        return $this->runAction();
    }

    /**
     * Returns a list the roles in given group.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/list_group_roles
     *
     * @param string $groupuuid
     *                          the UUID of the group
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     *               roles           The list of the role UUIDs and names, separated by a new-line ("\n")
     */
    public function list_group_roles(string $groupuuid = '', string $custom = ''): array
    {
        $this->queryParams              = [];
        $this->queryParams['action']    = 'list_group_roles';
        $this->queryParams['groupuuid'] = $groupuuid;
        $this->queryParams['custom']    = $custom;

        return $this->runAction();
    }

    /**
     * Returns a list of the Second Life groups the bot is member of.
     * If you need to list groups of another avatar, use avatar_groups command.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/list_group_roles
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure.
     *               groups          The list of the group UUIDs and names, separated by a new-line ("\n")
     */
    public function listgroups(string $custom = ''): array
    {
        $this->queryParams           = [];
        $this->queryParams['action'] = 'listgroups';
        $this->queryParams['custom'] = $custom;

        return $this->runAction();
    }

    /**
     * Returns a list of the bot's inventory folder contents.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/listinventory
     *
     * @param string $uuid
     *                         The optional UUID of the folder. Leave blank to list the root folder.
     * @param string $extended
     *                         Set this to 1 to get an extended output. This output includes:
     *                         - the object's name becomes URL-encoded
     *                         - current owner's permissions column added
     *                         - next owner's permissions column added
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure.
     *               list            The contents of the selected folder (see format below).
     */
    public function listinventory(string $uuid = '', string $extended = '', string $custom = ''): array
    {
        $this->queryParams             = [];
        $this->queryParams['action']   = 'listinventory';
        $this->queryParams['uuid']     = $uuid;
        $this->queryParams['extended'] = $extended;
        $this->queryParams['custom']   = $custom;

        return $this->runAction();
    }

    /**
     * Initiates bot login sequence.
     *
     * @param string $location
     *                         The initial location to login. Leave blank for previous location.
     *                         Format: Region name/X/Y/Z
     *                         Use HOME instead of location to send the bot home (see examples below).
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function login(string $location = '', string $custom = ''): array
    {
        $this->queryParams             = [];
        $this->queryParams['action']   = 'login';
        $this->queryParams['location'] = $location;
        $this->queryParams['custom']   = $custom;

        return $this->runAction();
    }

    /**
     * Initiates bot logout sequence.
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function logout(string $custom = ''): array
    {
        // Clean the current query array.
        $this->queryParams           = [];
        $this->queryParams['action'] = 'logout';
        $this->queryParams['custom'] = $custom;

        return $this->runAction();
    }

    /**
     * Start or stop bot movement and rotations.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/move
     *
     * @param string $instruction
     *                            The movement instruction. One of the following:
     *                            FORWARD     - start/stop the forward movement
     *                            BACKWARD    - start/stop the backward movement
     *                            LEFT        - start/stop turning to the left
     *                            RIGHT       - start/stop turning to the right
     *                            FLY         - start/stop flying
     *                            STOP        - stops all movements
     * @param string $param1
     *                            value which controls the "instruction" completion:
     *                            START       - starts "instruction"
     *                            STOP        - stops "instruction"
     *                            (this value is ignored for STOP instruction)
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function move(string $instruction = '', string $param1 = '', string $custom = ''): array
    {
        $this->queryParams                = [];
        $this->queryParams['action']      = 'move';
        $this->queryParams['instruction'] = $instruction;
        $this->queryParams['param1']      = $param1;
        $this->queryParams['custom']      = $custom;

        return $this->runAction();
    }

    /**
     * Returns avatar UUID by Second Life name.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/name2key
     *
     * @param string $name
     *                             the Second Life name of the avatar
     * @param int    $request_case
     *                             (optional) set to 1 if you want to get the exact avatar name case from Second Life
     *                             (see Name case)
     *
     * @return array
     *               result          OK - command completed successfully
     *               FAIL - command failed
     *               resulttext      Detailed reason for the failure.
     *               custom          The value from input "custom" parameter. See above.
     *               key             Avatar's UUID
     *               name            Second Life name you've sent
     *               normalname      Second Life name, normalized (see "Return value")
     */
    public function name2key(string $name = '', int $request_case = 1, string $custom = ''): array
    {
        $this->queryParams                 = [];
        $this->queryParams['action']       = 'name2key';
        $this->queryParams['name']         = $name;
        $this->queryParams['request_case'] = $request_case;
        $this->queryParams['custom']       = $custom;

        return $this->runAction();
    }

    /**
     * Offers friendship to a resident.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/offer_friendship
     *
     * @param string $avatar
     *                        the UUID of the resident
     * @param string $message
     *                        (optional) optional message to send
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function offer_friendship(string $avatar = '', string $message = '', string $custom = ''): array
    {
        $this->queryParams            = [];
        $this->queryParams['action']  = 'offer_friendship';
        $this->queryParams['avatar']  = $avatar;
        $this->queryParams['message'] = $message;
        $this->queryParams['custom']  = $custom;

        return $this->runAction();
    }

    /**
     * Sends a teleport offer to the resident.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/offer_teleport
     *
     * @param string $uuid
     *                        the UUID of the resident
     * @param string $message
     *                        (optional) optional message to send
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function offer_teleport(string $uuid = '', string $message = '', string $custom = ''): array
    {
        $this->queryParams            = [];
        $this->queryParams['action']  = 'offer_teleport';
        $this->queryParams['avatar']  = $uuid;
        $this->queryParams['message'] = $message;
        $this->queryParams['custom']  = $custom;

        return $this->runAction();
    }

    /**
     * Returns a lot of information about a parcel.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/parcel_info
     *
     * @param string $x
     *                         (optional) the X coordinate of the point in parcel
     * @param string $y
     *                         (optional) the Y coordinate of the point in parcel
     * @param string $getvalue
     *                         (optional) limit return list to this entry (see return values below)
     *
     * @return array
     *               result              OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext          Detailed reason for the failure.
     *               parcel_area         Parcel area, in m2.
     *               parcel_claimdate    The date when parcel was created/claimed.
     *               parcel_description  Description of the parcel.
     *               IMPORTANT: the description is being URL-escaped (it can contain new-line characters).
     *               Use llUnescapeURL() function to decode.
     *               parcel_group        The UUID of the parcel group.
     *               parcel_groupowned   Contains True if parcel is group owned.
     *               parcel_groupprims   Total number of primitives owned by the parcel group on this parcel.
     *               parcel_maxprims     Maximum number of primitives this parcel supports.
     *               parcel_totalprims   Total number of primitives on this parcel.
     *               parcel_musicurl     The music URL of the parcel.
     *               parcel_cleantime    Auto-return time (minutes).
     *               parcel_owner        Parcel owner UUID.
     *               parcel_ownerprims   The number of prims owned by the parcel owner (resident or group).
     *               parcel_saleprice    The price of the parcel (if parcel is for sale).
     *               parcel_snapshot     Key of parcel snapshot.
     *               parcel_landingpoint The landing point of the parcel.
     *               parcel_flags        Various flags, separated by commas.
     */
    public function parcel_info(string $x = '', string $y = '', string $getvalue = '', string $custom = ''): array
    {
        $this->queryParams             = [];
        $this->queryParams['action']   = 'parcel_info';
        $this->queryParams['x']        = $x;
        $this->queryParams['y']        = $y;
        $this->queryParams['getvalue'] = $getvalue;
        $this->queryParams['custom']   = $custom;

        return $this->runAction();
    }

    /**
     * Commands bot to rebake its appearance (reload its clothing and skin textures).
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/rebake
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function rebake(string $custom = ''): array
    {
        $this->queryParams           = [];
        $this->queryParams['action'] = 'rebake';
        $this->queryParams['custom'] = $custom;

        return $this->runAction();
    }

    /**
     * Virtually "presses" a pop-up dialog button (which was displayed by an in-world script).
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/reply_dialog
     *
     * @param int    $channel
     *                        the dialog channel (either positive or negative value)
     * @param string $object
     *                        UUID of the object which sent us the dialog
     * @param string $button
     *                        the text of the dialog button to press
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function reply_dialog(
        int $channel = -1,
        string $object = '',
        string $button = '',
        string $custom = ''
    ): array {
        $this->queryParams            = [];
        $this->queryParams['action']  = 'reply_dialog';
        $this->queryParams['channel'] = $channel;
        $this->queryParams['object']  = $object;
        $this->queryParams['button']  = $button;
        $this->queryParams['custom']  = $custom;

        return $this->runAction();
    }

    /**
     * Says message over a specific chat channel.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/say_chat_channel
     *
     * @param int    $channel
     *                        the channel to say message over (0 - public chat)
     * @param string $message
     *                        The message to send
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function say_chat_channel(int $channel = -1, string $message = '', string $custom = ''): array
    {
        $this->queryParams            = [];
        $this->queryParams['action']  = 'say_chat_channel';
        $this->queryParams['channel'] = $channel;
        $this->queryParams['message'] = $message;
        $this->queryParams['custom']  = $custom;

        return $this->runAction();
    }

    /**
     * Sends a message to group chat.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/send_group_im
     *
     * @param string $groupuuid
     *                          the UUID of the group
     * @param string $message
     *                          the text to send (can contain international characters)
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function send_group_im(string $groupuuid = '', string $message = '', string $custom = ''): array
    {
        $this->queryParams              = [];
        $this->queryParams['action']    = 'send_group_im';
        $this->queryParams['groupuuid'] = $groupuuid;
        $this->queryParams['message']   = $message;
        $this->queryParams['custom']    = $custom;

        return $this->runAction();
    }

    /**
     * Sends a notice to the group.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/send_notice
     *
     * @param string $groupuuid
     *                           the UUID of the group
     * @param string $subject
     *                           the subject of the notice (can't contain international characters)
     * @param string $text
     *                           the text of the notice (can contain international characters)
     * @param string $attachment
     *                           (optional) inventory UUID of the attachment (see below)
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function send_notice(
        string $groupuuid = '',
        string $subject = '',
        string $text = '',
        string $attachment = '',
        string $custom = ''
    ): array {
        $this->queryParams               = [];
        $this->queryParams['action']     = 'send_notice';
        $this->queryParams['groupuuid']  = $groupuuid;
        $this->queryParams['subject']    = $subject;
        $this->queryParams['text']       = $text;
        $this->queryParams['attachment'] = $attachment;
        $this->queryParams['custom']     = $custom;

        return $this->runAction();
    }

    /**
     * Specifies your HTTP callback script to receive bot events (invitations, IMs etc).
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/set_http_callback
     *
     * @param string $url
     *                       The URL of your HTTP script. This script will get POST requests from the bot.
     *                       Send an empty URL to disable HTTP callback.
     * @param string $events
     *                       The events you want to receive, separated by a comma. See
     *                       https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/set_http_callback/HTTP_Bot_Callback_Events
     *                       for complete a list of events. Separate events by comma if you want to monitor several of
     *                       them: events=group_invite,teleport_offer Specify events=all to monitor all possible
     *                       events.
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function set_http_callback(string $url = '', string $events = '', string $custom = ''): array
    {
        $this->queryParams           = [];
        $this->queryParams['action'] = 'set_http_callback';
        $this->queryParams['url']    = $url;
        $this->queryParams['events'] = $events;
        $this->queryParams['custom'] = $custom;

        return $this->runAction();
    }

    /**
     * Puts member of a group in a specific role.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/setrole
     *
     * @param string $groupuuid
     *                          the UUID of the group
     * @param string $roleuuid
     *                          the UUID of the group role. "Everyone" role is 00000000-0000-0000-0000-000000000000
     * @param string $member
     *                          the UUID of the avatar which should be moved to the specific role
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function setrole(
        string $groupuuid = '',
        string $roleuuid = '',
        string $member = '',
        string $custom = ''
    ): array {
        $this->queryParams              = [];
        $this->queryParams['action']    = 'setrole';
        $this->queryParams['groupuuid'] = $groupuuid;
        $this->queryParams['roleuuid']  = $roleuuid;
        $this->queryParams['member']    = $member;
        $this->queryParams['custom']    = $custom;

        return $this->runAction();
    }

    /**
     * Control access to the sim.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/sim_access
     *
     * @param string $avatar
     *                            The resident to manage access for
     * @param string $operation
     *                            The operation to perform. One of the following:
     *                            ban         - add to ban list
     *                            unban       - remove from ban list
     *                            allow       - add to allowed list
     *                            disallow    - remove from allowed list
     * @param int    $all_estates
     *                            (optional) If TRUE (or 1), perform operation for all estates available for bot
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function sim_access(
        string $avatar = '',
        string $operation = '',
        int $all_estates = 0,
        string $custom = ''
    ): array {
        $this->queryParams                = [];
        $this->queryParams['action']      = 'sim_access';
        $this->queryParams['avatar']      = $avatar;
        $this->queryParams['operation']   = $operation;
        $this->queryParams['all_estates'] = $all_estates;
        $this->queryParams['custom']      = $custom;

        return $this->runAction();
    }

    /**
     * Kicks resident (without banning from the sim).
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/sim_kick
     *
     * @param string $avatar
     *                       The resident to eject from the sim
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function sim_kick(string $avatar = '', string $custom = ''): array
    {
        $this->queryParams           = [];
        $this->queryParams['action'] = 'sim_kick';
        $this->queryParams['avatar'] = $avatar;
        $this->queryParams['custom'] = $custom;

        return $this->runAction();
    }

    /**
     * Begins the sim restart routine. Also used to cancel the restart routine previously started.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/sim_restart
     *
     * @param string $state
     *                      The restart state:
     *                      begin   - begin restarting current sim (the restart occurs in 120 seconds)
     *                      cancel  - stop restarting sim
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function sim_restart(string $state = '', string $custom = ''): array
    {
        $this->queryParams           = [];
        $this->queryParams['action'] = 'sim_restart';
        $this->queryParams['state']  = $state;
        $this->queryParams['custom'] = $custom;

        return $this->runAction();
    }

    /**
     * Returns objects of the specific resident from the sim.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/sim_return
     *
     * @param string $scripted
     *                            Set to TRUE (or 1) to return scripted object only
     * @param string $other
     *                            Set to TRUE (or 1) to return objects on others land
     * @param int    $all_estates
     *                            Set to TRUE (or 1) to return on all estates available for bot
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function sim_return(
        string $scripted = '',
        string $other = '',
        int $all_estates = 0,
        string $custom = ''
    ): array {
        $this->queryParams                = [];
        $this->queryParams['action']      = 'sim_return';
        $this->queryParams['scripted']    = $scripted;
        $this->queryParams['other']       = $other;
        $this->queryParams['all_estates'] = $all_estates;
        $this->queryParams['custom']      = $custom;

        return $this->runAction();
    }

    /**
     * Sends a message to all visitors of the sim.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/sim_send_message
     *
     * @param string $message
     *                        Set to TRUE (or 1) to return scripted object only
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function sim_send_message(string $message = '', string $custom = ''): array
    {
        $this->queryParams            = [];
        $this->queryParams['action']  = 'sim_send_message';
        $this->queryParams['message'] = $message;
        $this->queryParams['custom']  = $custom;

        return $this->runAction();
    }

    /**
     * Commands bot to sit on a specific prim. Allows saving this object as a permanent location.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/sit
     *
     * @param string $uuid
     *                     the UUID of the object to sit on. Use NONE instead of UUID to stand up.
     * @param string $save
     *                     (optional) set this parameter to 1 to save the UUID as a permanent sitting location.
     *                     Bot will sit on this object after relog, crash and sim restart.
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function sit(string $uuid = '', string $save = '', string $custom = ''): array
    {
        $this->queryParams           = [];
        $this->queryParams['action'] = 'sit';
        $this->queryParams['uuid']   = $uuid;
        $this->queryParams['save']   = $save;
        $this->queryParams['custom'] = $custom;

        return $this->runAction();
    }

    /**
     * Returns the online status of the bot.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/status
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function status(string $custom = ''): array
    {
        $this->queryParams           = [];
        $this->queryParams['action'] = 'status';
        $this->queryParams['custom'] = $custom;

        return $this->runAction();
    }

    /**
     * Removes a clothing item, body part or attachment (the opposite of the
     * https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/wear command).
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/takeoff
     *
     * @param string $uuid
     *                     The inventory UUID of the item. Use the
     *                     http://www.mysmartbots.com/docs/Personal_Bot_Control_Panel or
     *                     https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/listinventory API command to
     *                     get this UUID.
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function takeoff(string $uuid = '', string $custom = ''): array
    {
        $this->queryParams           = [];
        $this->queryParams['action'] = 'takeoff';
        $this->queryParams['uuid']   = $uuid;
        $this->queryParams['custom'] = $custom;

        return $this->runAction();
    }

    /**
     * Teleports bot to specific location.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/teleport
     *
     * @param string $location
     *                         address of the new location
     *                         Format: Region name/X/Y/Z
     *                         Use HOME instead of location to send the bot home (see examples below)
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function teleport(string $location = '', string $custom = ''): array
    {
        $this->queryParams             = [];
        $this->queryParams['action']   = 'teleport';
        $this->queryParams['location'] = $location;
        $this->queryParams['custom']   = $custom;

        return $this->runAction();
    }

    /**
     * Touches an object attached to the bot (HUD or attachment).
     * The attached object is being selected by name, and the button to be clicked - by link number.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/touch_attachment
     *
     * @param string $objectname
     *                           the name of the attached object (exact, including all spaces)
     * @param string $linkset
     *                           the link number of the object to touch. See the "Comments" section below.
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure.
     *               custom          The value from input "custom" parameter. See above.
     *               Additional "resulttext" values are available:
     *               OBJECT NOT FOUND    - The attachment object you've specified was not found
     *               LINK NOT FOUND      - The object has been found, but it contains less prims than your
     *               "linkset" value
     */
    public function touch_attachment(string $objectname = '', string $linkset = '', string $custom = ''): array
    {
        $this->queryParams               = [];
        $this->queryParams['action']     = 'touch_attachment';
        $this->queryParams['objectname'] = $objectname;
        $this->queryParams['linkset']    = $linkset;
        $this->queryParams['custom']     = $custom;

        return $this->runAction();
    }

    /**
     * Touches a prim in-world.
     * The command touches the prim in-world, locating it by UUID (also see
     * https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/touch_prim_coord API call).
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/touch_prim
     *
     * @param string $uuid
     *                     the UUID of the required prim
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function touch_prim(string $uuid = '', string $custom = ''): array
    {
        $this->queryParams           = [];
        $this->queryParams['action'] = 'touch_prim';
        $this->queryParams['uuid']   = $uuid;
        $this->queryParams['custom'] = $custom;

        return $this->runAction();
    }

    /**
     * Touches a prim in-world by using its coordinates.
     * This command tries to locate the object with specific coordinates, and touches it. The approximate coordinates
     * may be given, with a specified precision.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/touch_prim_coord
     *
     * @param float $x
     *                         the X coordinate (integer or float)
     * @param float $y
     *                         the Y coordinate (integer or float)
     * @param float $z
     *                         the Z coordinate (integer or float)
     * @param float $precision
     *                         (optional) the precision. Default 0.5 meters
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function touch_prim_coord(
        float $x = 0.0,
        float $y = 0.0,
        float $z = 0.0,
        float $precision = 0.0,
        string $custom = ''
    ): array {
        $this->queryParams              = [];
        $this->queryParams['action']    = 'touch_prim_coord';
        $this->queryParams['x']         = $x;
        $this->queryParams['y']         = $y;
        $this->queryParams['z']         = $z;
        $this->queryParams['precision'] = $precision;
        $this->queryParams['custom']    = $custom;

        return $this->runAction();
    }

    /**
     * Commands bot to wear a clothing item, body part or attach an object.
     *
     * @see https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/wear
     *
     * @param string $uuid
     *                     The inventory UUID of the item. Use the
     *                     http://www.mysmartbots.com/docs/Personal_Bot_Control_Panel or
     *                     https://www.mysmartbots.com/dev/docs/HTTP_API/Bot_Commands/listinventory API command to
     *                     get this UUID.
     *
     * @return array
     *               result          OK      - command completed successfully
     *               FAIL    - command failed
     *               resulttext      Detailed reason for the failure
     */
    public function wear(string $uuid = '', string $custom = ''): array
    {
        $this->queryParams           = [];
        $this->queryParams['action'] = 'wear';
        $this->queryParams['uuid']   = $uuid;
        $this->queryParams['custom'] = $custom;

        return $this->runAction();
    }
}
