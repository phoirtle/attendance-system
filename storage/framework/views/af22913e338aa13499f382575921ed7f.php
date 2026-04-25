
<?php $__env->startSection('title', 'Manage Employees — Heartstrings'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width:980px;margin:0 auto;padding:24px 20px 48px;">

    <div class="fade-in" style="margin-bottom:28px;">
        <p style="font-size:0.8rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:rgba(107,34,50,0.55);margin:0 0 4px;">Admin Portal</p>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">Manage <em>Employees</em></h1>
    </div>

    <?php if(session('success')): ?>
    <div class="flash flash-success fade-in" style="margin-bottom:16px;"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="fade-in delay-1" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <span style="font-size:0.85rem;color:rgba(107,34,50,0.60);"><?php echo e($users->count()); ?> employees</span>
        <a href="<?php echo e(route('admin.users.create')); ?>" class="btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;border-radius:12px;padding:10px 20px;font-size:0.85rem;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Employee
        </a>
    </div>

    <div class="glass-strong panel fade-in delay-2" style="background:rgba(255,249,245,0.60);padding:0;overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="data-table" style="min-width:600px;">
                <thead><tr>
                    <th style="padding-left:24px;">Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th style="padding-right:24px;text-align:right;">Actions</th>
                </tr></thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="padding-left:24px;font-weight:600;"><?php echo e($u->name); ?></td>
                    <td><?php echo e($u->email); ?></td>
                    <td><?php echo e($u->department ?? '—'); ?></td>
                    <td style="padding-right:24px;text-align:right;">
                        <a href="<?php echo e(route('admin.users.edit', $u)); ?>" class="btn-outline" style="text-decoration:none;padding:6px 14px;font-size:0.78rem;border-radius:8px;margin-right:6px;">Edit</a>
                        <form method="POST" action="<?php echo e(route('admin.users.destroy', $u)); ?>" style="display:inline;" onsubmit="return confirm('Delete this employee?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn-outline" style="padding:6px 14px;font-size:0.78rem;border-radius:8px;color:#BE0822;border-color:rgba(190,8,34,0.35);">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" style="text-align:center;padding:40px;color:rgba(107,34,50,0.45);">No employees found</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Windows\Downloads\attendance-system\resources\views/admin/users/index.blade.php ENDPATH**/ ?>