import { useState } from 'react';

type AuthView = 'login' | 'register' | 'forgot';

interface AuthPageProps {
  onLogin: (asAdmin?: boolean) => void;
}

export default function AuthPage({ onLogin }: AuthPageProps) {
  const [view, setView] = useState<AuthView>('login');
  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState('');
  const [errors, setErrors] = useState<Record<string, string>>({});

  // Login form state
  const [loginEmail, setLoginEmail] = useState('');
  const [loginPassword, setLoginPassword] = useState('');
  const [showPass, setShowPass] = useState(false);

  // Register form state
  const [regName, setRegName] = useState('');
  const [regEmail, setRegEmail] = useState('');
  const [regPassword, setRegPassword] = useState('');
  const [regConfirm, setRegConfirm] = useState('');

  // Forgot form state
  const [forgotEmail, setForgotEmail] = useState('');

  const validate = (fields: Record<string, string>) => {
    const errs: Record<string, string> = {};
    Object.entries(fields).forEach(([key, val]) => {
      if (!val.trim()) errs[key] = 'This field is required';
      if ((key === 'email' || key === 'loginEmail' || key === 'regEmail' || key === 'forgotEmail') && val && !val.includes('@'))
        errs[key] = 'Enter a valid email address';
    });
    return errs;
  };

  const handleLogin = (e: React.FormEvent) => {
    e.preventDefault();
    const errs = validate({ loginEmail, loginPassword });
    if (Object.keys(errs).length) { setErrors(errs); return; }
    setErrors({});
    setLoading(true);
    setTimeout(() => {
      setLoading(false);
      const isAdmin = loginEmail.includes('arya') || loginEmail === 'admin';
      onLogin(isAdmin);
    }, 1000);
  };

  const handleRegister = (e: React.FormEvent) => {
    e.preventDefault();
    const errs = validate({ regName, regEmail, regPassword, regConfirm });
    if (regPassword && regConfirm && regPassword !== regConfirm)
      errs.regConfirm = 'Passwords do not match';
    if (regPassword && regPassword.length < 8)
      errs.regPassword = 'Password must be at least 8 characters';
    if (Object.keys(errs).length) { setErrors(errs); return; }
    setErrors({});
    setLoading(true);
    setTimeout(() => {
      setLoading(false);
      setSuccess('Account created successfully! You can now log in.');
      setTimeout(() => { setView('login'); setSuccess(''); }, 2000);
    }, 1000);
  };

  const handleForgot = (e: React.FormEvent) => {
    e.preventDefault();
    const errs = validate({ forgotEmail });
    if (Object.keys(errs).length) { setErrors(errs); return; }
    setErrors({});
    setLoading(true);
    setTimeout(() => {
      setLoading(false);
      setSuccess('Reset link sent! Check your email inbox.');
    }, 1000);
  };

  const InputField = ({ label, id, type = 'text', value, onChange, error, placeholder, right }: {
    label: string; id: string; type?: string; value: string;
    onChange: (v: string) => void; error?: string; placeholder?: string; right?: React.ReactNode;
  }) => (
    <div className="space-y-1.5">
      <label htmlFor={id} className="text-xs font-semibold text-[#475569] uppercase tracking-wide">{label}</label>
      <div className="relative">
        <input
          id={id}
          type={type}
          value={value}
          onChange={e => onChange(e.target.value)}
          placeholder={placeholder}
          className={`w-full px-4 py-2.5 rounded-xl border text-sm text-[#1E293B] placeholder-[#94A3B8] outline-none focus:ring-2 focus:ring-[#0BC5C1]/30 focus:border-[#0BC5C1] transition-all ${
            error ? 'border-red-400 bg-red-50' : 'border-[#E2E8F0] bg-white hover:border-[#CBD5E1]'
          } ${right ? 'pr-10' : ''}`}
        />
        {right && <div className="absolute right-3 top-1/2 -translate-y-1/2">{right}</div>}
      </div>
      {error && <p className="text-xs text-red-500 flex items-center gap-1"><span>⚠</span>{error}</p>}
    </div>
  );

  return (
    <div className="min-h-screen bg-[#F0FAFA] flex">
      {/* Left panel */}
      <div className="hidden lg:flex flex-col w-[420px] bg-[#0BC5C1] p-10 relative overflow-hidden shrink-0">
        <div className="absolute inset-0 opacity-10">
          <div className="absolute top-10 left-10 w-40 h-40 rounded-full bg-white" />
          <div className="absolute bottom-20 right-5 w-64 h-64 rounded-full bg-white" />
          <div className="absolute top-1/2 left-1/4 w-20 h-20 rounded-full bg-white" />
        </div>
        <div className="relative z-10 flex-1 flex flex-col justify-between">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white font-bold text-lg">J</div>
            <span className="font-display font-800 text-2xl text-white">JARA</span>
          </div>
          <div>
            <h2 className="font-display font-800 text-3xl text-white leading-tight mb-4">
              Manage your tasks.<br />Collaborate with your team.
            </h2>
            <p className="text-white/80 text-sm leading-relaxed">
              JARA helps you organize projects, track deadlines, and collaborate seamlessly with your team — all in one place.
            </p>
            <div className="mt-8 space-y-3">
              {['Create & manage projects', 'Track task progress', 'Collaborate with teammates', 'Monitor deadlines'].map(f => (
                <div key={f} className="flex items-center gap-2.5">
                  <div className="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
                    <svg viewBox="0 0 24 24" fill="none" stroke="white" strokeWidth="2.5" className="w-3 h-3">
                      <path d="M20 6L9 17l-5-5" />
                    </svg>
                  </div>
                  <span className="text-white/90 text-sm">{f}</span>
                </div>
              ))}
            </div>
          </div>
          <p className="text-white/50 text-xs">© 2026 JARA. All rights reserved.</p>
        </div>
      </div>

      {/* Right panel */}
      <div className="flex-1 flex items-center justify-center p-6">
        <div className="w-full max-w-md">
          {/* Logo for mobile */}
          <div className="lg:hidden flex items-center gap-2 justify-center mb-8">
            <div className="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold" style={{ background: '#0BC5C1' }}>J</div>
            <span className="font-display font-800 text-2xl text-[#1E293B]">JARA</span>
          </div>

          <div className="bg-white rounded-2xl p-8 shadow-sm border border-[#E2E8F0]">
            {/* Success state */}
            {success && (
              <div className="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-2">
                <span className="text-emerald-500">✓</span>
                <p className="text-sm text-emerald-700">{success}</p>
              </div>
            )}

            {/* Login */}
            {view === 'login' && (
              <>
                <div className="mb-6">
                  <h2 className="font-display font-800 text-2xl text-[#1E293B]">Welcome back</h2>
                  <p className="text-sm text-[#64748B] mt-1">Sign in to your JARA account</p>
                </div>
                <form onSubmit={handleLogin} className="space-y-4">
                  <InputField label="Email" id="loginEmail" type="email" value={loginEmail} onChange={setLoginEmail} error={errors.loginEmail} placeholder="you@company.com" />
                  <InputField
                    label="Password" id="loginPassword" type={showPass ? 'text' : 'password'} value={loginPassword} onChange={setLoginPassword} error={errors.loginPassword} placeholder="••••••••"
                    right={
                      <button type="button" onClick={() => setShowPass(!showPass)} className="text-[#94A3B8] hover:text-[#64748B]">
                        {showPass
                          ? <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-4 h-4"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24M1 1l22 22" /></svg>
                          : <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-4 h-4"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" /><circle cx="12" cy="12" r="3" /></svg>
                        }
                      </button>
                    }
                  />
                  <div className="flex justify-end">
                    <button type="button" onClick={() => { setView('forgot'); setErrors({}); }} className="text-xs text-[#0BC5C1] hover:underline">
                      Forgot password?
                    </button>
                  </div>
                  <button
                    type="submit"
                    disabled={loading}
                    className="w-full py-2.5 rounded-xl bg-[#0BC5C1] text-white font-semibold text-sm hover:bg-[#0AAEAA] active:scale-95 disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                  >
                    {loading && <div className="w-4 h-4 border-2 border-white/40 border-t-white rounded-full animate-spin" />}
                    {loading ? 'Signing in...' : 'Sign in'}
                  </button>

                  {/* Demo shortcuts */}
                  <div className="pt-2 space-y-1.5">
                    <p className="text-xs text-[#94A3B8] text-center">Demo shortcuts:</p>
                    <div className="grid grid-cols-2 gap-2">
                      <button type="button" onClick={() => onLogin(false)} className="text-xs py-1.5 px-3 rounded-lg border border-[#E2E8F0] text-[#64748B] hover:bg-[#F8FAFC]">Login as User</button>
                      <button type="button" onClick={() => onLogin(true)} className="text-xs py-1.5 px-3 rounded-lg border border-[#E2E8F0] text-[#64748B] hover:bg-[#F8FAFC]">Login as Admin</button>
                    </div>
                  </div>
                </form>
                <p className="text-center text-sm text-[#64748B] mt-6">
                  Don't have an account?{' '}
                  <button onClick={() => { setView('register'); setErrors({}); }} className="text-[#0BC5C1] font-semibold hover:underline">Sign up</button>
                </p>
              </>
            )}

            {/* Register */}
            {view === 'register' && (
              <>
                <div className="mb-6">
                  <h2 className="font-display font-800 text-2xl text-[#1E293B]">Create account</h2>
                  <p className="text-sm text-[#64748B] mt-1">Join JARA and start managing tasks</p>
                </div>
                <form onSubmit={handleRegister} className="space-y-4">
                  <InputField label="Full Name" id="regName" value={regName} onChange={setRegName} error={errors.regName} placeholder="Your full name" />
                  <InputField label="Email" id="regEmail" type="email" value={regEmail} onChange={setRegEmail} error={errors.regEmail} placeholder="you@company.com" />
                  <InputField label="Password" id="regPassword" type="password" value={regPassword} onChange={setRegPassword} error={errors.regPassword} placeholder="Min. 8 characters" />
                  <InputField label="Confirm Password" id="regConfirm" type="password" value={regConfirm} onChange={setRegConfirm} error={errors.regConfirm} placeholder="Repeat your password" />
                  <button
                    type="submit"
                    disabled={loading}
                    className="w-full py-2.5 rounded-xl bg-[#0BC5C1] text-white font-semibold text-sm hover:bg-[#0AAEAA] active:scale-95 disabled:opacity-60 flex items-center justify-center gap-2"
                  >
                    {loading && <div className="w-4 h-4 border-2 border-white/40 border-t-white rounded-full animate-spin" />}
                    {loading ? 'Creating account...' : 'Create account'}
                  </button>
                </form>
                <p className="text-center text-sm text-[#64748B] mt-6">
                  Already have an account?{' '}
                  <button onClick={() => { setView('login'); setErrors({}); }} className="text-[#0BC5C1] font-semibold hover:underline">Sign in</button>
                </p>
              </>
            )}

            {/* Forgot password */}
            {view === 'forgot' && (
              <>
                <div className="mb-6">
                  <button onClick={() => { setView('login'); setErrors({}); setSuccess(''); }} className="flex items-center gap-1 text-sm text-[#64748B] hover:text-[#0BC5C1] mb-4">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="w-4 h-4"><path d="M19 12H5M12 5l-7 7 7 7" /></svg>
                    Back to login
                  </button>
                  <h2 className="font-display font-800 text-2xl text-[#1E293B]">Reset password</h2>
                  <p className="text-sm text-[#64748B] mt-1">We'll send a reset link to your email</p>
                </div>
                <form onSubmit={handleForgot} className="space-y-4">
                  <InputField label="Email" id="forgotEmail" type="email" value={forgotEmail} onChange={setForgotEmail} error={errors.forgotEmail} placeholder="you@company.com" />
                  <button
                    type="submit"
                    disabled={loading || !!success}
                    className="w-full py-2.5 rounded-xl bg-[#0BC5C1] text-white font-semibold text-sm hover:bg-[#0AAEAA] active:scale-95 disabled:opacity-60 flex items-center justify-center gap-2"
                  >
                    {loading && <div className="w-4 h-4 border-2 border-white/40 border-t-white rounded-full animate-spin" />}
                    {loading ? 'Sending...' : 'Send reset link'}
                  </button>
                </form>
              </>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
