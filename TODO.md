# TODO: Implement Profile Photo Editing with Automatic Avatar Generation

## Tasks
- [x] Create migration to add `profile_photo_path` to users table
- [x] Update User model to include `profile_photo_path` in fillable and add accessor for avatar
- [x] Update ProfileUpdateRequest to validate image file uploads
- [x] Update ProfileController update method to handle file upload and storage
- [x] Update profile edit form to include file input for photo upload
- [x] Create helper method to generate SVG avatar from first letter of username if no photo exists
- [x] Update navigation view to display profile photo or generated avatar
- [x] Update home view to display profile photo or generated avatar

## Followup Steps
- [x] Run migrations
- [ ] Test profile photo upload (fixed enctype issue)
- [ ] Verify avatar generation for users without photos
