<?php $__env->startSection('title', 'Personal Details — Heartstrings'); ?>
<?php $__env->startSection('content'); ?>
<div style="max-width:480px;margin:0 auto;padding:24px 20px 48px;">
    <a href="<?php echo e(route('profile.show')); ?>" style="display:inline-flex;align-items:center;gap:6px;font-size:0.83rem;color:#BE0822;text-decoration:none;font-weight:500;margin-bottom:20px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Settings
    </a>
    <h2 style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:600;color:#3d1a22;margin:0 0 24px;">Personal <em>Details</em></h2>

    <div class="glass-strong panel" style="background:rgba(255,249,245,0.65);">
        <?php if(session('success')): ?>
        <div class="flash flash-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
        <div class="flash flash-error"><?php echo e($errors->first()); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('profile.details.update')); ?>">
            <?php echo csrf_field(); ?>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Full Name</label>
                <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" class="input-glass" placeholder="Your full name" required>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Email Address</label>
                <input type="email" value="<?php echo e($user->email); ?>" class="input-glass" disabled style="opacity:0.60;cursor:not-allowed;">
                <p style="font-size:0.72rem;color:rgba(107,34,50,0.45);margin:5px 0 0;">Email cannot be changed here</p>
            </div>
            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Department</label>
                <input type="text" name="department" value="<?php echo e(old('department', $user->department)); ?>" class="input-glass" placeholder="e.g., Engineering, Marketing">
            </div>
            <button type="submit" class="btn-primary" style="width:100%;border-radius:14px;">Save Details</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Windows\Downloads\attendance-system\resources\views/profile/details.blade.php ENDPATH**/ ?>