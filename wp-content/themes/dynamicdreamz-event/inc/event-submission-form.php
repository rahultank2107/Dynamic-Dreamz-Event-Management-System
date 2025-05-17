<?php
function dynamicdreamz_render_event_submission_form() {
    ?>
    <form id="dd-event-form" enctype="multipart/form-data" method="post" action="">
        <label>Event Title</label>
        <input type="text" name="event_title" placeholder="Event Title" required>

        <label>Start Date/Time</label>
        <input type="datetime-local" name="event_start" required>

        <label>End Date/Time</label>
        <input type="datetime-local" name="event_end" required>

        <label>Organizer Name</label>
        <input type="text" name="organizer_name" placeholder="Organizer Name" required>

        <label>Organizer Email</label>
        <input type="email" name="organizer_email" placeholder="Organizer Email" required>

        <label>Organizer Phone</label>
        <input type="text" id="organizer_phone" name="organizer_phone" placeholder="+91-123-123-1234" required>

        <label>Venue Selection</label>
        <select name="event_type" id="event_type">
            <option value="">Select Type</option>
            <option value="online">Online</option>
            <option value="venue">At Venue</option>
        </select>

        <div id="venue_fields" style="display:none;">
            <input type="text" name="venue" placeholder="Venue Name">
            <input type="text" name="venue_coords" placeholder="Map Coordinates">
        </div>

        <label>Ticket Price</label>
        <input type="number" name="ticket_price" placeholder="Ticket Price" required>

        <label>Event Image</label>
        <input type="file" name="event_image" accept=".jpg,.jpeg,.png" required>

        <input type="text" name="event_bot_field" style="display:none;">

        <button type="submit">Submit</button>
        <p id="form-message"></p>
    </form>
    <?php
}
