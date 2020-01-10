<div class="panel panel-default">
    <div class="panel-heading">
        <p>خيارات التحكم</p>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <form action="search.php" method="post">
                <div class="col-xs-12 col-md-6">
                    <div>
                        <label>الحد الادنى</label>
                        <select name="minage" class="form-control">
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                        </select>
                        <label>الحد الاقصى</label>
                        <select name="maxage" class="form-control">
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div>
                        <label>المحافظة</label>
                        <select name="town" class="form-control">
                            <option value="0">choose</option>
                            <option value="cairo">القاهرة</option>
                            <option value="alexandria">الاسكندرية</option>
                            <option value="giza">الجيزة</option>
                            <option value="aswan">اسوان</option>
                        </select>
                        <label>الحالة الاجتماعية</label>
                        <select class="form-control">
                            <option value="0">choose</option>
                            <option value="1">متزوج / متزوجة</option>
                            <option value="2">اعزب / عزباء</option>
                            <option value="3">خاطب / مخطوبة</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12"><br>
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-search"></i> بحث</button>
                </div>
            </form>
        </div>
    </div>
</div>