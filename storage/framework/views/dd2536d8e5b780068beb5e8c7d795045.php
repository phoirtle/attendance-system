
<?php $__env->startSection('title', 'My Leaves — Heartstrings'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width:980px;margin:0 auto;padding:24px 20px 48px;">

    <div class="fade-in" style="margin-bottom:28px;">
        <p style="font-size:0.8rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:rgba(107,34,50,0.55);margin:0 0 4px;">Leave Requests</p>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">My <em>Leaves</em></h1>
    </div>

    <?php if(session('success')): ?>
    <div class="flash flash-success fade-in" style="margin-bottom:16px;"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="flash flash-error fade-in" style="margin-bottom:16px;"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="fade-in delay-1" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <div style="display:flex;gap:16px;align-items:center;flex-wrap:wrap;">
            <div class="glass panel" style="padding:10px 18px;display:flex;gap:8px;align-items:center;">
                <span style="font-size:0.78rem;color:rgba(107,34,50,0.60);">Quota</span>
                <span style="font-size:1rem;font-weight:700;color:#3d1a22;"><?php echo e(auth()->user()->remainingLeaveDays()); ?> / 12 days</span>
            </div>
        </div>
        <a href="<?php echo e(route('leaves.create')); ?>" class="btn-primary" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;border-radius:12px;padding:10px 20px;font-size:0.85rem;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Request Leave
        </a>
    </div>

    <div class="glass-strong panel fade-in delay-2" style="background:rgba(255,249,245,0.60);padding:0;overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="data-table" style="min-width:700px;">
                <thead><tr>
                    <th style="padding-left:24px;">Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Duration</th>
                    <th>Reason</th>
                    <th>Document</th>
                    <th style="padding-right:24px;">Status</th>
                    <th style="padding-right:24px;">Action</th>
                </tr></thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="padding-left:24px;font-weight:500;text-transform:capitalize;"><?php echo e($leave->type); ?></td>
                    <td><?php echo e($leave->start_date->format('d M Y')); ?></td>
                    <td><?php echo e($leave->end_date->format('d M Y')); ?></td>
                    <td><?php echo e($leave->duration()); ?> days</td>
                    <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="<?php echo e($leave->reason); ?>"><?php echo e($leave->reason); ?></td>
                    <td>
                        <?php if($leave->document_path): ?>
                            <a href="<?php echo e(asset('storage/' . $leave->document_path)); ?>" target="_blank" style="color:#BE0822;font-size:0.78rem;font-weight:500;text-decoration:none;">View</a>
                        <?php else: ?>
                            <span style="color:rgba(107,34,50,0.40);font-size:0.78rem;">—</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding-right:24px;">
                        <span class="badge <?php echo e($leave->status === 'approved' ? 'badge-success' : ($leave->status === 'rejected' ? 'badge-danger' : 'badge-warning')); ?>">
                            <?php echo e(ucfirst($leave->status)); ?>

                        </span>
                    </td>
                    <td style="padding-right:24px;">
                        <?php if($leave->status !== 'approved'): ?>
                        <form method="POST" action="<?php echo e(route('leaves.destroy', $leave)); ?>" style="display:inline;" onsubmit="return confirm('Cancel this leave request?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn-outline" style="padding:5px 12px;font-size:0.75rem;border-radius:8px;color:#BE0822;border-color:rgba(190,8,34,0.30);">Cancel</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" style="text-align:center;padding:40px;color:rgba(107,34,50,0.45);">No leave requests yet</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Windows\Downloads\attendance-system\resources\views/leaves/index.blade.php ENDPATH**/ ?>