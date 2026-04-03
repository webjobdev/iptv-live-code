<style>
    .options {
        display: flex;
        gap: 18px;
        margin-bottom: 25px;
        flex-wrap: wrap;
        padding: 5px;
    }

    .gradient-item {
        position: relative;
        cursor: pointer;
        display: inline-block;
    }

    .gradient-item input[type="radio"] {
        display: none;
    }

    .gradient-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: block;
        position: relative;
    }

    .gradient-item:hover .gradient-circle {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }

    /* Selection State */
    .gradient-item input[type="radio"]:checked + .gradient-circle {
        transform: scale(1.15);
        box-shadow: 0 0 0 3px #fff, 0 0 0 6px #e00000;
    }

    /* Checkmark Overlay */
    .gradient-item input[type="radio"]:checked + .gradient-circle::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 18px;
        font-weight: bold;
        text-shadow: 0 1px 4px rgba(0,0,0,0.4);
    }

    /* Gradient Palettes */
    .purple-1 { background: linear-gradient(135deg, #A855F7, #6D28D9); }
    .purple-2 { background: linear-gradient(135deg, #7C3AED, #5B21B6); }
    .blue     { background: linear-gradient(135deg, #3B82F6, #1D4ED8); }
    .green    { background: linear-gradient(135deg, #22C55E, #15803D); }
    .orange   { background: linear-gradient(135deg, #F97316, #C2410C); }
    .red      { background: linear-gradient(135deg, #EF4444, #B91C1C); }

    /* Responsive Design Settings */
    @media (max-width: 768px) {
        .options {
            justify-content: center;
            gap: 14px;
        }

        .gradient-circle {
            width: 42px;
            height: 42px;
            border-width: 3px;
        }
    }

    @media (max-width: 480px) {
        .options {
            gap: 10px;
        }
        .gradient-circle {
            width: 36px;
            height: 36px;
            border-width: 2px;
        }
    }
</style>

<div class="options">
    <label class="gradient-item">
        <input type="radio" name="poster-gradient" value="linear-gradient(135deg, #A855F7, #6D28D9)" ng-model="rows.gradient">
        <span class="gradient-circle purple-1"></span>
    </label>
    <label class="gradient-item">
        <input type="radio" name="poster-gradient" value="linear-gradient(135deg, #7C3AED, #5B21B6)" ng-model="rows.gradient">
        <span class="gradient-circle purple-2"></span>
    </label>
    <label class="gradient-item">
        <input type="radio" name="poster-gradient" value="linear-gradient(135deg, #3B82F6, #1D4ED8)" ng-model="rows.gradient">
        <span class="gradient-circle blue"></span>
    </label>
    <label class="gradient-item">
        <input type="radio" name="poster-gradient" value="linear-gradient(135deg, #22C55E, #15803D)" ng-model="rows.gradient">
        <span class="gradient-circle green"></span>
    </label>
    <label class="gradient-item">
        <input type="radio" name="poster-gradient" value="linear-gradient(135deg, #F97316, #C2410C)" ng-model="rows.gradient">
        <span class="gradient-circle orange"></span>
    </label>
    <label class="gradient-item">
        <input type="radio" name="poster-gradient" value="linear-gradient(135deg, #EF4444, #B91C1C)" ng-model="rows.gradient">
        <span class="gradient-circle red"></span>
    </label>
</div>