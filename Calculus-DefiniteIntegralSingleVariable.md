
|![封面：牛顿-莱布尼茨公式](./images/Calculus/牛顿-莱布尼茨公式.jpg)|
|:--:|
| 封面：牛顿-莱布尼茨公式 |

<div style="page-break-after: always;"></div>

# 求一元定积分

---

**求积分$I=\int_{-1}^1 \frac{x^2}{1+e^x} \mathrm{d}x$。**

$\begin{aligned}
I
&=\int_{-1}^1 \frac{x^2}{1+e^x} \mathrm{d}x =\int_0^1 \frac{x^2}{1+e^x} \mathrm{d}x + \int_{-1}^0 \frac{x^2}{1+e^x} \mathrm{d}x \\
&\xlongequal{\quad 第二部分替换t=-x \quad}\int_0^1 \frac{x^2}{1+e^x} \mathrm{d}x + \int_{1}^0 \frac{(-x)^2}{1+e^{-x}} \mathrm{d}(-x) \\
&=\int_0^1 \frac{x^2}{1+e^x} \mathrm{d}x + \int_0^1 \frac{x^2}{1+e^{-x}} \mathrm{d}x =\int_0^1 \frac{x^2}{1+e^x} + \frac{x^2}{1+e^{-x}} \mathrm{d}x \\
&=\int_0^1 \frac{x^2(2+e^{-x}+e^x)}{2+e^{-x}+e^x} \mathrm{d}x =\int_0^1 x^2 \mathrm{d}x \\
&= \left.\frac{1}{3}x^3 \right|_0^1 = \frac{1}{3}
\end{aligned}$

---

**求积分$I=\int_0^{\frac{\pi}{2}} \frac{\sin 2x}{1+\sin^4 x} \mathrm{d}x$。**

$\begin{aligned}
I
&=\int_0^{\frac{\pi}{2}} \frac{\sin 2x}{1+\sin^4 x} \mathrm{d}x \\
&\xlongequal{\quad \sin 2x = 2\sin x \cos x \quad} \int_0^{\frac{\pi}{2}} \frac{2\sin x \cos x}{1+\sin^4 x} \mathrm{d}x \\
&\xlongequal{\quad t=\sin x \quad} \int_0^1 \frac{2t}{1+t^4} \mathrm{d}t =\int_0^1 \frac{1}{1+t^4} \mathrm{d}t^2 \\
&\xlongequal{\quad u=t^2 \quad} \int_0^1 \frac{1}{1+u^2} \mathrm{d}u =\left.\arctan u \right|_0^1 =\frac{\pi}{4}
\end{aligned}$

---
