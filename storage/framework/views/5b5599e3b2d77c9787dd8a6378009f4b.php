
<?php $__env->startSection('title', 'Request Leave — Heartstrings'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width:540px;margin:0 auto;padding:24px 20px 48px;">

    <div class="fade-in" style="margin-bottom:28px;">
        <a href="<?php echo e(route('leaves.index')); ?>" style="display:inline-flex;align-items:center;gap:6px;font-size:0.83rem;color:#BE0822;text-decoration:none;font-weight:500;margin-bottom:20px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
            Back to My Leaves
        </a>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:600;color:#3d1a22;margin:0;letter-spacing:-0.02em;">Request <em>Leave</em></h1>
        <p style="font-size:0.85rem;color:rgba(107,34,50,0.55);margin-top:6px;">Remaining quota: <strong><?php echo e(auth()->user()->remainingLeaveDays()); ?> / 12 days</strong></p>
    </div>

    <div class="glass-strong panel fade-in delay-1">
        <form method="POST" action="<?php echo e(route('leaves.store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Leave Type</label>
                <select name="type" id="leaveType" class="input-glass" required onchange="toggleDocumentField()">
                    <option value="">Select type</option>
                    <option value="annual" <?php echo e(old('type') === 'annual' ? 'selected' : ''); ?>>Annual Leave</option>
                    <option value="sick" <?php echo e(old('type') === 'sick' ? 'selected' : ''); ?>>Sick Leave</option>
                    <option value="permission" <?php echo e(old('type') === 'permission' ? 'selected' : ''); ?>>Permission</option>
                </select>
                <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="font-size:0.78rem;color:#BE0822;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;">
                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Start Date</label>
                    <input type="date" name="start_date" value="<?php echo e(old('start_date')); ?>" class="input-glass" required>
                    <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="font-size:0.78rem;color:#BE0822;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">End Date</label>
                    <input type="date" name="end_date" value="<?php echo e(old('end_date')); ?>" class="input-glass" required>
                    <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="font-size:0.78rem;color:#BE0822;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Reason</label>
                <textarea name="reason" class="input-glass" rows="4" placeholder="Describe your reason..." required style="resize:vertical;"><?php echo e(old('reason')); ?></textarea>
                <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="font-size:0.78rem;color:#BE0822;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div id="documentField" style="margin-bottom:24px;display:none;">
                <label style="display:block;font-size:0.78rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:#6b2232;margin-bottom:7px;">Medical Document <span style="text-transform:none;font-weight:400;color:rgba(107,34,50,0.50);">(Required for sick leave)</span></label>
                <input type="file" name="document" id="documentInput" class="input-glass" accept=".pdf,.jpg,.jpeg,.png" style="padding:10px 14px;font-size:0.85rem;">
                <?php $__errorArgs = ['document'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="font-size:0.78rem;color:#BE0822;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit" class="btn-primary" style="width:100%;border-radius:12px;">Submit Request</button>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleDocumentField() {
    const type = document.getElementById('leaveType').value;
    const field = document.getElementById('documentField');
    const input = document.getElementById('documentInput');
    if (type === 'sick') {
        field.style.display = 'block';
        input.required = true;
    } else {
        field.style.display = 'none';
        input.required = false;
    }
}
document.addEventListener('DOMContentLoaded', toggleDocumentField);
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Windows\Downloads\attendance-system\resources\views/leaves/create.blade.php ENDPATH**/ ?>