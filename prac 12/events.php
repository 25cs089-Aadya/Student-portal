<?php
require_once "auth.php";
require_once "db.php";

$messages = [
    "created" => "Event created successfully.",
    "updated" => "Event updated successfully.",
    "deleted" => "Event deleted successfully.",
    "invalid" => "Please enter valid event details.",
    "type" => "Invalid file type. Upload JPG, PNG or WebP only.",
    "size" => "Poster must be 2 MB or smaller.",
    "upload" => "Poster upload failed.",
    "missing" => "Event not found.",
    "linked" => "This event has registrations and cannot be deleted.",
    "token" => "Form expired. Refresh the page and try again.",
    "failed" => "Could not save changes. Please try again."
];
$success = in_array($_GET["msg"] ?? "", ["created", "updated", "deleted"], true);
$message = $messages[$_GET["msg"] ?? ""] ?? "";

$editing = false;
$event = ["event_id" => "", "event_name" => "", "event_date" => "", "venue" => "",
          "description" => "", "status" => "Upcoming", "poster" => ""];

if (isset($_GET["edit"])) {
    $id = filter_var($_GET["edit"], FILTER_VALIDATE_INT);
    if (!$id || $id < 1) { header("Location: events.php?msg=missing"); exit; }
    $stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $found = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$found) { header("Location: events.php?msg=missing"); exit; }
    $event = $found;
    $editing = true;
}

$result = $conn->query("SELECT * FROM events ORDER BY event_date DESC, event_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>StudentHub | Event Management</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header class="site-header"><h1>🎓 StudentHub</h1><p>Practical 12 · Event Management</p></header>
<main class="container">
<nav><a href="/prac%2010/admin_dashboard.php">Admin Dashboard</a> | <a href="/prac%2011/manage_students.php">Manage Students</a> | <a href="/prac%2010/logout.php">Logout</a></nav>
<h2>Event Management</h2>
<p class="intro">Create, edit, and manage college events and their posters.</p>
<?php if ($message !== ""): ?>
<div class="notice <?= $success ? "success" : "error" ?>" role="alert"><?= e($message) ?></div>
<?php endif; ?>
<section class="panel" id="form">
<h3><?= $editing ? "Edit Event" : "Add New Event" ?></h3>
<form action="save_event.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?= e($_SESSION['event_token']) ?>">
<input type="hidden" name="id" value="<?= e($event['event_id']) ?>">
<div class="fields">
<div><label for="title">Event Name</label><input id="title" name="title" type="text" maxlength="100" required value="<?= e($event['event_name']) ?>"></div>
<div><label for="date">Date and Time</label><input id="date" name="date" type="datetime-local" required value="<?= $event['event_date'] ? e(date('Y-m-d\TH:i', strtotime($event['event_date']))) : '' ?>"></div>
<div><label for="venue">Venue</label><input id="venue" name="venue" type="text" maxlength="100" required value="<?= e($event['venue']) ?>"></div>
<div><label for="status">Status</label><select id="status" name="status">
<?php foreach (["Upcoming", "Ongoing", "Completed", "Cancelled"] as $s): ?>
<option value="<?= $s ?>" <?= ($event['status'] ?? 'Upcoming') === $s ? 'selected' : '' ?>><?= $s ?></option>
<?php endforeach; ?></select></div>
<div class="wide"><label for="description">Description</label><textarea id="description" name="description" maxlength="5000" required><?= e($event['description']) ?></textarea></div>
<div class="wide"><label for="poster">Event Poster (JPG, PNG or WebP, max 2 MB)</label><input id="poster" name="poster" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
<?php if ($editing && $event['poster']): ?><p>Current poster:</p><img class="preview" src="<?= e($event['poster']) ?>" alt="Current event poster"><?php endif; ?></div>
</div>
<button type="submit"><?= $editing ? "Update Event" : "Add Event" ?></button>
<?php if ($editing): ?><a class="cancel" href="events.php">Cancel</a><?php endif; ?>
</form></section>
<section class="panel"><h3>All Events</h3>
<?php if ($result->num_rows === 0): ?><p>No events found.</p><?php else: ?>
<div class="cards">
<?php while ($row = $result->fetch_assoc()): ?>
<article class="event-card">
<?php if ($row['poster']): ?><img class="event-poster" src="<?= e($row['poster']) ?>" alt="<?= e($row['event_name']) ?> poster">
<?php else: ?><div class="no-poster">No poster uploaded</div><?php endif; ?>
<div class="details"><h4><?= e($row['event_name']) ?></h4>
<p><strong>Date:</strong> <?= e(date('d M Y, h:i A', strtotime($row['event_date']))) ?></p>
<p><strong>Venue:</strong> <?= e($row['venue']) ?></p>
<p><strong>Status:</strong> <?= e($row['status']) ?></p>
<p><?= nl2br(e($row['description'])) ?></p>
<div class="actions"><a class="edit" href="events.php?edit=<?= (int)$row['event_id'] ?>#form">Edit</a>
<form method="POST" action="delete_event.php" onsubmit="return confirm('Delete this event?');">
<input type="hidden" name="token" value="<?= e($_SESSION['event_token']) ?>">
<input type="hidden" name="id" value="<?= (int)$row['event_id'] ?>">
<button class="delete" type="submit">Delete</button></form></div></div>
</article>
<?php endwhile; ?>
</div><?php endif; ?></section>
</main></body></html>
