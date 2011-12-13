Ext.define('Ext.calendar.util.Date', {
    
    singleton: true,
    
    diffDays: function(start, end) {
        var day = 1000 * 60 * 60 * 24,
            clear = Ext.Date.clearTime,
            diff = clear(end, true).getTime() - clear(start, true).getTime();
        
        return Math.ceil(diff / day);
    },

    copyTime: function(fromDt, toDt) {
        var dt = Ext.Date.clone(toDt);
        dt.setHours(
            fromDt.getHours(),
            fromDt.getMinutes(),
            fromDt.getSeconds(),
            fromDt.getMilliseconds());

        return dt;
    },

    compare: function(dt1, dt2, precise) {
        if (precise !== true) {
            dt1 = Ext.Date.clone(dt1);
            dt1.setMilliseconds(0);
            dt2 = Ext.Date.clone(dt2);
            dt2.setMilliseconds(0);
        }
        return dt2.getTime() - dt1.getTime();
    },

    // private helper fn
    maxOrMin: function(max) {
        var dt = (max ? 0: Number.MAX_VALUE),
        i = 0,
        args = arguments[1],
        ln = args.length;
        for (; i < ln; i++) {
            dt = Math[max ? 'max': 'min'](dt, args[i].getTime());
        }
        return new Date(dt);
    },

    max: function() {
        return this.maxOrMin.apply(this, [true, arguments]);
    },

    min: function() {
        return this.maxOrMin.apply(this, [false, arguments]);
    },
    
    today: function() {
        return Ext.Date.clearTime(new Date());
    },
    
    add: function(dt, o) {
        if (!o) {
            return dt;
        }
        Ext.applyIf(o, {
            millis:  0,
            seconds: 0,
            minutes: 0,
            hours:   0,
            days:    0,
            weeks:   0,
            months:  0,
            years:   0,
            clearTime: false
        });
        
        var ms = o.millis,
            s  = o.seconds * 1000,
            m  = o.minutes * 1000 * 60,
            h  = o.hours   * 1000 * 60 * 60,
            d  = o.days    * 1000 * 60 * 60 * 24,
            w  = o.weeks   * 1000 * 60 * 60 * 24 * 7,
            sumUpToWeeks = ms + s + m + h + d + w,
            newDt = new Date();
        
        newDt.setTime(dt.getTime() + sumUpToWeeks);
        
        if (o.months) {
            newDt = Ext.Date.add(dt, Ext.Date.MONTH, o.months);
        }
        if (o.years) {
            newDt.setFullYear(newDt.getFullYear() + o.years);
        }
        return o.clearTime ? Ext.Date.clearTime(newDt) : newDt;
    }
});