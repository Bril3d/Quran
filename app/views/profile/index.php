<div class="container profile-page my-5">
    <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-md-4">
            <div class="card profile-sidebar mb-4">
                <div class="card-body text-center">
                    <div class="profile-avatar">
                        <i class="fas fa-user-circle fa-5x"></i>
                    </div>
                    <h4 class="mt-3"><?= htmlspecialchars($user['name']) ?></h4>
                    <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                    <div class="profile-stats mb-3">
                        <div class="row">
                            <div class="col">
                                <div class="stat-value"><?= count($bookmarks) ?></div>
                                <div class="stat-label">العلامات المرجعية</div>
                            </div>
                            <div class="col">
                                <div class="stat-value"><?= date_diff(date_create($user['created_at']), date_create('now'))->days ?></div>
                                <div class="stat-label">أيام الإشتراك</div>
                            </div>
                        </div>
                    </div>
                    <a href="<?= baseUrl() ?>/settings" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-cog me-1"></i> إعدادات الحساب
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Profile Content -->
        <div class="col-md-8">
            <!-- Flash Message -->
            <?php if (isset($_GET['updated']) && $_GET['updated'] == 'true'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    تم تحديث الملف الشخصي بنجاح!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            <?php endif; ?>
            
            <!-- Profile Info Card -->
            <div class="card mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i> المعلومات الشخصية</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= baseUrl() ?>/profile/update" method="post">
                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم</label>
                            <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= htmlspecialchars($name ?? $user['name']) ?>">
                            <?php if (isset($errors['name'])): ?>
                                <div class="invalid-feedback">
                                    <?= $errors['name'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= htmlspecialchars($email ?? $user['email']) ?>">
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback">
                                    <?= $errors['email'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h6 class="mb-3">تغيير كلمة المرور</h6>
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                            <input type="password" class="form-control <?= isset($errors['current_password']) ? 'is-invalid' : '' ?>" id="current_password" name="current_password">
                            <?php if (isset($errors['current_password'])): ?>
                                <div class="invalid-feedback">
                                    <?= $errors['current_password'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
                            <input type="password" class="form-control <?= isset($errors['new_password']) ? 'is-invalid' : '' ?>" id="new_password" name="new_password">
                            <?php if (isset($errors['new_password'])): ?>
                                <div class="invalid-feedback">
                                    <?= $errors['new_password'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" id="confirm_password" name="confirm_password">
                            <?php if (isset($errors['confirm_password'])): ?>
                                <div class="invalid-feedback">
                                    <?= $errors['confirm_password'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </form>
                </div>
            </div>
            
            <!-- Bookmarks Card -->
            <div class="card mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0"><i class="fas fa-bookmark me-2"></i> العلامات المرجعية</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($bookmarks)): ?>
                        <p class="text-muted text-center">لا توجد علامات مرجعية حتى الآن</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>السورة</th>
                                        <th>الآية</th>
                                        <th>الوصف</th>
                                        <th>التاريخ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookmarks as $bookmark): ?>
                                        <tr>
                                            <td><?= $bookmark['surah_number'] ?></td>
                                            <td><?= $bookmark['ayah_number'] ?></td>
                                            <td><?= $bookmark['name'] ? htmlspecialchars($bookmark['name']) : '-' ?></td>
                                            <td><?= date('Y-m-d', strtotime($bookmark['created_at'])) ?></td>
                                            <td>
                                                <a href="<?= baseUrl() ?>/quran?surah=<?= $bookmark['surah_number'] ?>" class="btn btn-sm btn-outline-primary" title="الذهاب إلى الآية">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= baseUrl() ?>/api/quran/bookmark-delete/<?= $bookmark['id'] ?>" class="btn btn-sm btn-outline-danger" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذه العلامة المرجعية؟')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Reading History Card -->
            <div class="card mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i> سجل القراءة</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($readingHistory)): ?>
                        <p class="text-muted text-center">لا يوجد سجل قراءة حتى الآن</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>السورة</th>
                                        <th>الآية</th>
                                        <th>التاريخ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($readingHistory as $history): ?>
                                        <tr>
                                            <td><?= $history['surah_number'] ?></td>
                                            <td><?= $history['ayah_number'] ?></td>
                                            <td><?= date('Y-m-d H:i', strtotime($history['timestamp'])) ?></td>
                                            <td>
                                                <a href="<?= baseUrl() ?>/quran/<?= $history['surah_number'] ?>/<?= $history['ayah_number'] ?>" class="btn btn-sm btn-outline-primary" title="الذهاب إلى الآية">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?= baseUrl() ?>/profile/history" class="btn btn-sm btn-outline-secondary">عرض السجل الكامل</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div> 