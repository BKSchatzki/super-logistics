# Super Logistics

Super Logistics is a WordPress plugin that allows you to manage your logistics data in a simple and efficient way.

## Data Organization (engineering)

The plugin is organized in the following way:

### Deletion / Archiving

No data is ever deleted from the app. "Deleted" items are marked as _trashed_. 

In addition, all data can be archived by the appropriate user type.

### Entities

The following are considered the main entities of the plugin:

- clients (type 1)
- shows (type 2)
- carriers (type 3) - not implemented yet

Shows have their own model, and have accessory data stored in a shows table, however they should be accessed primarily through the entity model. In order to simplify access to properties like name, active, and trashed, id will refer to the id on the **entities table**.

Carriers will be implemented in the future. Currently they are simply text fields, and are not recorded in the app with any measures for consistency.

### Users

Users are stored in the wp_users table and are associated with entities through the sl_users_entities table.

Deleted / Archived users **do not** have access to the app! They will be _considered_ **logged out** even when they are logged in! This is because of the deletion method used in the app, which does not actually delete data. What this does allow for is restoring of delelted or archived users. If a user is no longer doing business with the logistics manager but may return in the future, they can be safely archived or deleted to restrict access, but easily restored if they return :).

When checking if a user is logged in, the app checks its own records through the User model, and that has restrictions blocking active and deleted users to all but wordpress admins.

#### Roles
Roles are kept track of the prefix_usermeta table in the columns with the key prefix_capabilities. The roles are stored as a serialized array.
* this means that the roles must be serialized and deserialized.
* WordPress also has some handy functions for reading / inserting them.
* If you are having a hard time gettin role data while writing new code, be wary of not using the table prefix while accessing prefix_capabilities. **the column**.

#### Status
For deletion / archiving, there is a designated table, **sl_user_status**, which keeps track of that information.

#### Entities
Some entities require association with one or more users, namely clients, which users can belong to (IRL), and shows, which users can be responsibly for (client employees). This is recorded in the **sl_users_entities** table.

### Transactions

## User Types / Permissions

There are **4** user types in the app 
excluding wordpress admin - which is for developers only.

- Internal Admin
- Internal Employee
- Client Admin
- Client Employee

## Improvements

- [ ] Convert to Typescript
- [ ] End to end testing
- [ ] API Endpoint testing
- [ ] More robust error handling
- [ ] More branding
- [ ] Form validation

