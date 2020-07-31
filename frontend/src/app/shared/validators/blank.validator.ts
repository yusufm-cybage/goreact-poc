
import { FormControlName } from '@angular/forms';

export class BlankSpaceValidator {
    public static validate(control: FormControlName) {
        let blankSpace_REGEXP = /.*\S.*/;
        return blankSpace_REGEXP.test(control.value) ? null : { validateBlankSpace: { valid: false } };
    }
}
