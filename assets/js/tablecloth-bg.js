export function initTableclothBackground(config) {
  const defaults = {
    colorLight: '#ff9100',
    colorDark: '#ff8000',
    gridSize: 40,
    mouseRadius: 218,
    waveSpeed: 9,
    opacity: 0.88
  };
  const cfg = { ...defaults, ...config };
  const DEV = false; // set to true to enable sampling overlay

  const canvas = document.getElementById('bg');
  if (!canvas) {
    console.warn('Canvas element with id="bg" not found');
    return;
  }

  const ctx = canvas.getContext('2d', { alpha: true });
  let w = 0, h = 0, dpr = 1, time = 0;
  let mouse = { x: -9999, y: -9999 };
  let frameCount = 0, lastFpsTime = performance.now(), fps = 0;

  function hexToRgb(hex) {
    const n = parseInt(hex.replace('#',''), 16);
    return { r: (n>>16)&255, g: (n>>8)&255, b: n&255 };
  }
  const rgbLight = hexToRgb(cfg.colorLight);
  const rgbDark  = hexToRgb(cfg.colorDark);

  function resize() {
    dpr = Math.max(1, window.devicePixelRatio || 1);
    w = window.innerWidth;
    h = window.innerHeight;
    canvas.style.position = 'fixed';
    canvas.style.inset = '0';
    canvas.style.zIndex = '-1';
    canvas.style.pointerEvents = 'none';
    canvas.style.backgroundColor = '#fff3e0'; // light warm beige background for blending
    // IMPORTANT: no CSS opacity here
    canvas.width  = Math.floor(w * dpr);
    canvas.height = Math.floor(h * dpr);
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0); // scale to CSS pixels
  }

  let lastDebugTime = -1000; // Initialize to sample immediately
  let debugAlphaLight = 0.65;
  let debugAlphaDark = 0.75;

  function animate(now) {
    requestAnimationFrame(animate);
    frameCount++;
    if (now - lastFpsTime >= 1000) {
      fps = frameCount;
      frameCount = 0;
      lastFpsTime = now;
    }

    time += 0.01 * (cfg.waveSpeed / 10);
    ctx.clearRect(0, 0, w, h);
    ctx.globalCompositeOperation = 'source-over';
    ctx.globalAlpha = 1; // Ensure full opacity, no double-fading

    const gs = cfg.gridSize;
    
    // Sample alpha values for debug overlay (once per second)
    if (now - lastDebugTime >= 1000) {
      // Sample a dark and light cell near center
      const sampleX = Math.floor(w / 2 / gs) * gs;
      const sampleY = Math.floor(h / 2 / gs) * gs;
      
      // Sample dark cell
      let dx = sampleX - mouse.x;
      let dy = sampleY - mouse.y;
      let dist = Math.hypot(dx, dy);
      let nearMouse = Math.max(0, 1 - dist / cfg.mouseRadius);
      const alphaBaseDark = 0.75;
      debugAlphaDark = Math.min(1, (alphaBaseDark + nearMouse * 0.25) * cfg.opacity);
      
      // Sample light cell (offset by one grid square)
      dx = (sampleX + gs) - mouse.x;
      dy = sampleY - mouse.y;
      dist = Math.hypot(dx, dy);
      nearMouse = Math.max(0, 1 - dist / cfg.mouseRadius);
      const alphaBaseLight = 0.65;
      debugAlphaLight = Math.min(1, (alphaBaseLight + nearMouse * 0.25) * cfg.opacity);
      
      lastDebugTime = now;
    }

    for (let x = 0; x < w + gs; x += gs) {
      for (let y = 0; y < h + gs; y += gs) {
        const dx = x - mouse.x;
        const dy = y - mouse.y;
        const dist = Math.hypot(dx, dy);
        const wave = Math.sin((x * 0.01) + (y * 0.01) + time) * 0.3;
        const mouseWave = Math.sin(dist * 0.005 - time * 2) * 0.2;
        const totalWave = wave + mouseWave;
        const offsetX = x + totalWave * 8;
        const offsetY = y + totalWave * 6;
        const gridX = Math.floor(offsetX / gs);
        const gridY = Math.floor(offsetY / gs);
        const isDark = ((gridX + gridY) % 2) === 0;
        const nearMouse = Math.max(0, 1 - dist / cfg.mouseRadius);
        
        // Enforce minimum alpha: 0.75 for dark, 0.65 for light
        const alphaBase = isDark ? 0.75 : 0.65;
        const a = Math.min(1, (alphaBase + nearMouse * 0.25) * cfg.opacity);

        const rgb = isDark ? rgbDark : rgbLight;
        ctx.fillStyle = `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, ${a})`;
        ctx.fillRect(x, y, gs, gs);
      }
    }

    if (DEV) {
      ctx.save();
      ctx.globalAlpha = 1;
      
      // Debug overlay in top-right corner (RGBA info)
      ctx.fillStyle = 'rgba(0, 0, 0, 0.7)';
      ctx.fillRect(w - 280, 10, 270, 50);
      ctx.fillStyle = '#fff';
      ctx.font = '11px monospace';
      ctx.fillText(`Light RGBA: ${cfg.colorLight} α≈${debugAlphaLight.toFixed(2)}`, w - 275, 28);
      ctx.fillText(`Dark RGBA: ${cfg.colorDark} α≈${debugAlphaDark.toFixed(2)}`, w - 275, 44);
      
      // DPR and FPS in top-left corner
      ctx.fillStyle = 'rgba(0,0,0,0.5)';
      ctx.fillRect(10, 10, 160, 60);
      ctx.fillStyle = '#fff';
      ctx.font = '12px sans-serif';
      ctx.fillText(`DPR: ${dpr.toFixed(2)}`, 20, 30);
      ctx.fillText(`FPS: ${fps}`, 20, 48);
      
      ctx.restore();
    }
  }

  function onMouseMove(e) { mouse = { x: e.clientX, y: e.clientY }; }
  function onClickSample(e) {
    if (!DEV) return;
    const x = Math.floor(e.clientX * dpr);
    const y = Math.floor(e.clientY * dpr);
    const data = ctx.getImageData(x, y, 1, 1).data;
    console.log('Sample RGBA at click:', data);
  }

  window.addEventListener('resize', resize);
  window.addEventListener('mousemove', onMouseMove, { passive: true });
  window.addEventListener('click', onClickSample);
  resize();
  requestAnimationFrame(animate);
}

