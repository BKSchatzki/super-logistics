# POD Attachment Capability (v3.1.1 -- Carrier POD Upload System)

## Background

The original POD implementation (v3.0.0) generated a thinkSTG-branded PDF embedding the freight photo captured during receiving (`image_path`). Client feedback from D. Jones clarified this was a miscommunication: the actual need is to attach carrier-provided proof-of-delivery documents (PDF or JPEG) to receivers, separate from the freight photo.

> "What we need to be able to do is attach either a PDF or a jpeg (separate from the photo of freight) that has the POD from the carrier." -- D. Jones

## v3.1.1 Implementation

### Data model

- Added `pod_path VARCHAR(255) NULL` to `sl_transactions` (alongside the existing `image_path` for freight photos).
- `pod_path` added to `Transaction::$fillable` and exposed in `TransactionTransformer`.

### Backend endpoints

| Endpoint                    | Method      | Permission   | Purpose                                                     |
| --------------------------- | ----------- | ------------ | ----------------------------------------------------------- |
| `POST receiving/pod/upload` | `uploadPOD` | `isInternal` | Upload carrier POD (PDF, JPEG, PNG)                         |
| `POST receiving/pod/delete` | `deletePOD` | `isInternal` | Delete carrier POD file and clear `pod_path`                |
| `POST receiving/pod`        | `printPOD`  | `isLoggedIn` | View/print carrier POD (with per-transaction authorization) |

### Upload handler

`Controller::handleImageUpload()` now accepts an optional `$mimes` parameter. POD uploads pass an extended list including `application/pdf`. Default behavior (image-only) is unchanged for existing callers.

### POD generator

`PODDocGenerator` was rewritten:

- **PDF files:** Read and return bytes directly (pass-through). No wrapping.
- **Image files:** Wrap in a minimal single-page TCPDF document for consistent print behavior.
- The previous thinkSTG-branded layout was removed.

### Authorization

`printPOD` was changed from `isInternal` to `isLoggedIn` so client users can view/print. A new `authorizeTransactionAccess()` method enforces scoping:

- Internal users: full access.
- Client admins: scoped to their assigned client.
- Client employees: scoped to their assigned client and shows.
- All other user types: denied (deny-by-default).

### Frontend

`ReceiverDetails.vue` now has a dedicated "Carrier POD" panel:

- **Status indicator:** Shows attached filename or "No carrier POD attached."
- **View/Print POD:** Enabled for all logged-in users when a POD is attached.
- **Upload/Replace POD:** Internal users only. File picker accepts `.pdf`, `.jpg`, `.jpeg`, `.png`.
- **Delete POD:** Internal users only, with inline confirmation.

### Access model

| Action     | Internal | Client       |
| ---------- | -------- | ------------ |
| Upload     | Yes      | No           |
| View/Print | Yes      | Yes (scoped) |
| Delete     | Yes      | No           |

## Relationship to freight photo

The freight photo (`image_path`) captured via camera during the receiving form remains untouched. It is a separate field from the carrier POD (`pod_path`). Both can coexist on the same transaction.

## Superseded implementation

The v3.0.0 POD implementation (thinkSTG-branded PDF from `image_path`, internal-only access, no upload flow) has been fully replaced. The `PODDocGenerator` class was rewritten in place.
